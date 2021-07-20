<?php
/**
 * The Main Model.
 *
 * This model will be responsible for database communication to execute queries for cakes data.
 *
 * @category   Models
 * @package    App\Models
 * @author     Ofer Elfassi and Dekel Ben-david
 */

/**
 * Class MainModel
 */
class MainModel extends BaseModel
{
    protected $_table_name = 'Cakes';
    public $_cakes;

    /**
     * MainModel constructor.
     * calling the parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get the cakes tage from the database
     * @param array $cakes array of cakes objects
     */
    private function getCakesTags(&$cakes)
    {

        $tbl = array(
            "tblName" => $this->tables["tags"],
            "tblVars" => ["name", "id"]);
        $joins = [
            [
                "tblName" => $this->tables["cake_user_tag"],
                "on" => $this->tables["tags"].".id = ".$this->tables["cake_user_tag"].".TagId",
                "vars" => [],
            ]
        ];
        $where = "";
        foreach ($cakes as $index => $cake) {
            $where = $this->tables["cake_user_tag"].".CakeId = " . $cake["id"];
            $res = $this->getAndJoin("INNER JOIN", $tbl, $joins, $where);
            $cakes[$index] = array_merge($cake, ["tags" => $res]);
        }
    }

    /**
     * get the cakes ratings from the database
     * @param array $cakes array of cakes objects
     */
    private function getCakesRatings(&$cakes)
    {
        $tbl = array(
            "tblName" => $this->tables["ratings"],
            "tblVars" => [],
            "aggregate" => " AVG(".$this->tables["ratings"].".rating) AS rating "

        );
        $joins = [
            [
                "tblName" => $this->tables["cakes"],
                "on" => $this->tables["cakes"].".id = ".$this->tables["ratings"].".CakeId",
                "vars" => [],
            ]
        ];
        foreach ($cakes as $index => $cake) {
            $where = $this->tables["ratings"].".CakeId = " . $cake["id"];
            $res = $this->getAndJoin("INNER JOIN", $tbl, $joins, $where);
            $cakes[$index] = array_merge($cake, $res[0]);
        }
    }

    /**
     * calculate cake rating
     * @param numeric $cakeId cake id
     */
    private function calcCakeRating($cakeId)
    {
        $tbl = array(
            "tblName" => $this->tables["ratings"],
            "tblVars" => [],
            "aggregate" => " AVG(".$this->tables["ratings"].".rating) AS rating "

        );
        $joins = [
            [
                "tblName" => $this->tables["cakes"],
                "on" => $this->tables["cakes"].".id = ".$this->tables["ratings"].".CakeId",
                "vars" => [],
            ]
        ];
        $where = $this->tables["ratings"].".CakeId = " . $cakeId;
        $res = $this->getAndJoin("INNER JOIN", $tbl, $joins, $where);
        return $res;
    }




    /**
     * get related list of cakes from database based on the specified cake tags
     * @param array $cake cake object
     * $cake =[
     * 'man'  => (numeric) jjj
     * ]
     */
    private function getRelated(&$cake)
    {
        $tbl = array(
            "tblName" => $this->tables["cakes"],
            "tblVars" => ["name", "image", "image3d", "id"]
        );
        $cake["man"] ="kj";
        $joins = [];
        $inStr = "(";
        if (!$cake) return;
        foreach ($cake["tags"] as $index => $tag) {
            $inStr .= $tag["id"];
            if ($index != (count($cake["tags"]) - 1))
                $inStr .= ', ';
        }
        $inStr .= ") ";
        array_push($joins, [
            "tblName" => $this->tables["cake_user_tag"],
            "on" => $this->tables["cake_user_tag"].".CakeId = ".$this->tables["cakes"].".id AND ".$this->tables["cake_user_tag"].".TagId IN " . $inStr . " ",
            "vars" => [],
        ]);
        $where = $this->tables["cakes"].".id <> " . $cake["id"];
        $relatedCakes = $this->getAndJoin(" JOIN", $tbl, $joins, $where);
        $this->getCakesRatings($relatedCakes);
        $cake = array_merge($cake, ["related" => $relatedCakes]);
    }

    /**
     * get cake/s from database based on or more of the following parameters - user id, tags, search string, cake id
     * @param numeric $userId user id
     * @param array $tags cake tags
     * @param string $search search string
     * @param numeric $cakeId cake id
     * @return array|false
     */
    public function getCakes($userId = NULL, $tags = NULL, $search = NULL, $cakeId = NULL)
    {
        $where = NULL;
        $tbl = array(
            "tblName" => $this->tables["cakes"],
            "tblVars" => ["name", "image", "image3d", "id","UserId"]
        );
        $joins = [
            [
                "tblName" => $this->tables["users"],
                "on" => $this->tables["users"].".id = ".$this->tables["cakes"].".UserId",
                "vars" => ["nickname"],
            ]
        ];
        if ($userId) {
            $where = $this->tables["cakes"].".UserId = " . $userId;
        }
        if ($search) {
            $where = $this->tables["cakes"].".name LIKE '%" . $search . "%' ";
        }
        if ($cakeId) {
            $where = $this->tables["cakes"].".id = " . $cakeId;
            array_push($tbl["tblVars"], "description", "ingredients", "recipe", "bakingTime", "difficulty", "servings");
        }

        if ($tags && count($tags)) {

            $inStr = "(";
            foreach ($tags as $index => $tag) {
                $inStr .= $tag["TagId"];
                if ($index != (count($tags) - 1))
                    $inStr .= ', ';
            }
            $inStr .= ") ";
            array_push($joins, [
                "tblName" => $this->tables["cake_user_tag"],
                "on" => $this->tables["cake_user_tag"].".CakeId = ".$this->tables["cakes"].".id AND ".$this->tables["cake_user_tag"].".TagId IN " . $inStr . " ",
                "vars" => [],
            ]);
        }


        $this->_cakes = $this->getAndJoin(" JOIN", $tbl, $joins, $where);
        if (count($this->_cakes) == 0) return false;
        $this->getCakesTags($this->_cakes);
        if ($cakeId) {
            $this->getRelated($this->_cakes[0]);
        }
        $this->getCakesRatings($this->_cakes);
        return $this->_cakes;
    }

    /**
     * update cake in database based on updated cake data
     * @param numeric $cakeId id of the cake to be updated
     * @param array $data updated cake data
     */
    public function updateCake($cakeId, $data)
    {
        $this->edit($this->tables["cakes"], $data, $cakeId);
    }

    /**
     * delete cake from database based on cake id
     * @param string $cakeId
     */
    public function removeCake($cakeId)
    {
        $this->delete($this->tables["cakes"],"id",$cakeId,true);
    }

    /**
     * add new cake to the database
     * @param numeric $userId the id of the user that create the new cake
     * @param array $data new cake data
     * @return string new cake id
     */
    public function insertCake($userId, $data)
    {
        $allTags = $this->get($this->tables["tags"]);
        $data["cake"] += ["UserId" => $userId];
        $newCakeId = $this->create($this->tables["cakes"], $data["cake"]);
        foreach ($data["tags"] as $tag) {
            foreach ($allTags as $existingTag) {
                if ($tag == $existingTag["name"]) {
                    $this->create($this->tables["cake_user_tag"], ["CakeId" => $newCakeId, "TagId" => $existingTag["id"]]);
                }

            }
        }
        return $newCakeId;
    }

    /**
     * update/add cake rating based on cake id and user id
     * @param numeric $cakeId id of the cake to be rated
     * @param numeric $userId id of the user that rated the cake
     * @param numeric $rating rating value
     * @return array
     */
    public function updateRating($cakeId, $userId, $rating)
    {
        if ($this->exist($this->tables["ratings"], [
            ["colName" => "CakeId", "value" => $cakeId],
            ["colName" => "UserId", "value" => $userId]
        ])) {
            //update
            $this->edit($this->tables["ratings"], ["rating" => $rating], $cakeId, "CakeId", " UserId = " . $userId . " ");
        } else {
            //create
            $this->create($this->tables["ratings"], ["CakeId" => $cakeId, "UserId" => $userId, "rating" => $rating]);
        }
        return $this->calcCakeRating($cakeId);
    }


    /**
     * return the query results
     * @return array
     */
    public function getVars()
    {
        $tags = $this->get($this->tables["tags"]);
        return array("_cakes" => $this->_cakes, "tags" => $tags);
    }


}