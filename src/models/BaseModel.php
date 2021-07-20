<?php
/**
 * The abstract model class.
 *
 * This class allows connection to the database via PDO.
 *
 * @category   Database
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class BaseModel
{
    protected $_table_name = '';
    protected $db = NULL;
    public $tables = [
        "users"=>"tbl_users_203",
        "comments"=>"tbl_comments_203",
        "ratings"=>"tbl_ratings_203",
        "cake_user_tag"=>"tbl_cake_user_tag_203",
        "tags"=>"tbl_tags_203",
        "cakes"=>"tbl_cakes_203"];
//    public $tables = [
//        "users"=>"users",
//        "comments"=>"comments",
//        "ratings"=>"ratings",
//        "cake_user_tag"=>"cake_user_tag",
//        "tags"=>"tags",
//        "cakes"=>"cakes"];

    public function __construct()
    {
        global $db;
        $this->db = $db->conn;
    }

    /**
     * Checks if value exist in database table.
     * @param string $tblName the database table name.
     * @param array $conditions query conditions.
     * @return int
     */
    public function exist($tblName, $conditions)
    {
        $statement = "SELECT * FROM $tblName WHERE ";
        $values = [];
        foreach ($conditions as  $index=>$condition) {
            $statement.= " ".$condition["colName"] ."= ? ";
            if ($index != (count($conditions) - 1)) {
                $statement.= ' AND ';
            }
            array_push($values,$condition["value"]);
        }
        $stmt = $this->db->prepare($statement);
        $stmt->execute($values);
        return count($stmt->fetchAll());
    }

    /**
     * Insert new database row.
     * @param string $tblName the database table name.
     * @param array $data database new row data.
     * @return string the id of the created row.
     */
    public function create($tblName, $data)
    {
        $keys = array_keys($data);
        foreach ($data as $key => $value) {
            $data[":$key"] = $value;
            unset($data["$key"]);
        }
        $statement = "INSERT INTO $tblName (" . implode(", ", $keys) . ") ";
        $statement .= "VALUES ( :" . implode(", :", $keys) . ")";
        $stmt = $this->db->prepare($statement);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    /**
     * Update database table row
     * @param string $tblName the database table name.
     * @param array $data database updated row data.
     * @param string $id the id of the row to be updated
     * @param string $primary_key_name the name of the table primary key
     * @param array $where query where conditions
     * @return string the id of the updated row.
     */
    public function edit($tblName,$data, $id,$primary_key_name = "id",$where = NULL ){
        $keys = array_keys($data);
        foreach ($data as $key => $value) {
            $data[":$key"] = $value;
            unset($data["$key"]);
        }
        $data[":$primary_key_name"] = $id;
        $sql = "UPDATE $tblName SET";
        foreach ($keys as $key => $value) {
            $sql .= " $value = :$value";
            if ($key != (count($keys) - 1))
                $sql .= ', ';
        }
        $sql .= " WHERE $primary_key_name = :".$primary_key_name." ";
        if($where){
            $sql .= "AND ".$where;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $id;
    }


    /**
     * select data from database
     * @param string $tblName the database table name.
     * @param string $primary_key_name the name of the table primary key
     * @param string $id the id of the target data
     * @param boolean $single choosing single or multiple rows
     * @param string $select comma separated string representing column names to be selected
     * @param string $order_by representing orderBy options
     * @param numeric $limit limit the result count
     * @param array $where query where conditions
     * @param string $group_by representing groupBy options
     * @return array query results
     */
    public function get($tblName,$primary_key_name = NULL,$id = NULL, $single = FALSE, $select = '*',$order_by = NULL , $limit = NULL, $where=NULL,$group_by = NULL )
    {
        $statement = "SELECT $select FROM $tblName ";
        if ($id)
            $statement .= "WHERE $primary_key_name = :id ";
        else if($where){
            $statement .= "WHERE ";
            foreach ($where as $key => $value) {
                $statement .= "$key" . ' = ' . '"' . "$value" . '"';
                $statement .= " AND ";
            }
            $statement = preg_replace('/\W\w+\s*(\W*)$/', '$1', $statement);
        }
        if ($order_by)
            $statement .= "ORDER BY $order_by ";
        if ($single)
            $statement .= "LIMIT 1";
        else if ($limit)
            $statement .= "$limit";
        if($group_by){
            $statement .= " GROUP BY ".$group_by;
        }
        $stmt = $this->db->prepare($statement);
        if ($id)
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * select data from database with join tables conditions
     * @param string $joinType type of join clause
     * @param array $leftTbl the join left table conditions
     * @param array $joins array of joins table names and conditions
     * @param string $where query where conditions
     * @param string $groupBy representing groupBy options
     * @return array
     */
    public function getAndJoin($joinType, $leftTbl, $joins, $where=NULL, $groupBy=NULL){
        $statement = "SELECT DISTINCT " ;
        foreach ($leftTbl["tblVars"] as $var) {
            $statement .= $leftTbl["tblName"].".".$var. ", ";
        }
        if(isset($leftTbl["aggregate"])){
            $statement .= $leftTbl["aggregate"];
        }
        foreach ($joins as $join) {
            foreach ($join["vars"] as $var) {
                $statement .= $join["tblName"].".".$var. ", ";
            }
        }
        $statement = rtrim($statement, ", ");
        $statement .=" FROM  ".$leftTbl["tblName"];
        foreach ($joins as $join) {
            $statement .= " ".$joinType ." ".$join["tblName"]." ON ".$join["on"];
        }
        if($where){
            $statement .= " WHERE ". $where;
        }
        if($groupBy){
            $statement .= " GROUP BY ".$groupBy;
        }
        $stmt = $this->db->prepare($statement);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * delete row from database
     * @param string $tblName the database table name.
     * @param string $idName the name of the table primary key
     * @param string $id  the id of the target data
     * @param boolean $keepAutoIncrement choose id incrementing functionality
     * @return boolean
     */
    public function delete($tblName,$idName,$id,$keepAutoIncrement = false)
    {
        $stmt = $this->db->prepare("DELETE FROM $tblName WHERE $idName = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        if($keepAutoIncrement) {
            $stmt = $this->db->prepare("ALTER TABLE $tblName AUTO_INCREMENT = 1");
            $stmt->execute();
        }
        return $result;
    }

}