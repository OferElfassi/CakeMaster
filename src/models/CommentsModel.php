<?php

/**
 * The Comments Model.
 *
 * This model will be responsible for database communication to execute queries for comments data.
 *
 * @category   Models
 * @package    App\Models
 * @author     Ofer Elfassi and Dekel Ben-david
 */

/**
 * Class CommentsModel
 */
class CommentsModel extends BaseModel
{
    protected $_table_name = 'comments';

    /**
     * CommentsModel constructor.
     * calling the parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get comment/s from database based on cake id or specific comment id
     * @param numeric $cakeId the id of the cake to get related comments
     * @param numeric $commentId the id id of the specific comment
     * @return array
     */
    public function getComments($cakeId = NULL, $commentId = NULL)
    {
        $tbl = array(
            "tblName" => $this->tables["comments"],
            "tblVars" => ["content", "CakeId", "UserId", "id", "createdAt", "updatedAt"]
        );

        $joins = [
            [
                "tblName" => $this->tables["users"],
                "on" => $this->tables["users"].".id =".$this->tables["comments"].".UserId",
                "vars" => ["fullname"],
            ],
            [
                "tblName" => $this->tables["cakes"],
                "on" => $this->tables["cakes"].".id =".$this->tables["comments"].".CakeId",
                "vars" => [],
            ]
        ];
        if ($commentId) {
            $where = $this->tables["comments"].".id = " . $commentId;
        } else {
            $where = $this->tables["comments"].".CakeId = " . $cakeId;
        }

        return $this->getAndJoin(" JOIN ", $tbl, $joins, $where);
    }

    /**
     * add comment to database
     * @param array $comment comment data
     * @return mixed
     */
    public function addComment($comment)
    {
        $newId = $this->create($this->tables["comments"], $comment);
        $newComment =  $this->getComments(NULL, $newId);
        return $newComment[0];
    }

    /**
     * edit comment based on comment id and edited comment data
     * @param numeric $commentId the id of the comment to be updated
     * @param array $comment updated comment data
     * @return mixed
     */
    public function editComment($commentId, $comment)
    {
        $this->edit($this->tables["comments"], $comment, $commentId);
        $updatedComment =  $this->getComments(NULL, $commentId);
        return $updatedComment[0];
    }

    /**
     * delete comment from database based on comment id
     * @param numeric $commentId the id of the comment to be deleted
     */
    public function deleteComment($commentId)
    {
        $this->delete($this->tables["comments"], "id", $commentId, true);
    }


}