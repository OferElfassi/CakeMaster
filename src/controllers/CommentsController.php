<?php
/**
 * The Comments Controller.
 *
 * This controller will be responsible for handling cake comments functionality.
 *
 * @category   Controllers
 * @package    App\Controllers
 * @author     Ofer Elfassi and Dekel Ben-david
 */

include_once "src/models/CommentsModel.php";

/**
 * Class CommentsController
 */
class CommentsController extends BaseController
{


    /**
     * CommentsController constructor.
     * call parent constructor and instantiating CommentsModel object for database communication
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new CommentsModel();
    }

    /**
     * get comments from database using the commentsModel object based on the cake id sent in the request parameters
     * @param Request $req request for comments list with cake id parameter
     */
    public function getCommentsByCake($req)
    {
        $comments = $this->model->getComments($req->params["cakeId"]);
        foreach ($comments as $index => $comment) {
            $comments[$index] += ["created_by_current_user" => false];
            if ($this->is_loggedIn && $_SESSION["user"]["id"] == $comment["UserId"]) {
                $comments[$index]["created_by_current_user"] = true;
            }

        }
        http_response_code(200);
        echo json_encode($comments);

    }

    /**
     * add comment to database using the commentsModel object based on the comment data sent in the request body
     * @param Request $req request for adding comment with comment data in the request body
     */
    public function addComment($req)
    {
        try {
            $newComment = $this->model->addComment($req->body);
            $newComment += ["created_by_current_user" => false];
            if ($this->is_loggedIn && $_SESSION["user"]["id"] == $newComment["UserId"]) {
                $newComment["created_by_current_user"] = true;
            }
            http_response_code(200);
            echo json_encode($newComment);
        }catch (Exception $e){
            http_response_code(404);

        }

    }

    /**
     * edit comment using the commentsModel object based on the edited comment data sent in the request body and comment id sent in the request parameters
     * @param Request $req request for updating comment with comment data in the request body and comment id in the request parameters
     */
    public function editComment($req)
    {
        $updatedComment = $this->model->editComment($req->params["commentId"], $req->body);
        $updatedComment += ["created_by_current_user" => false];
        if ($this->is_loggedIn && $_SESSION["user"]["id"] == $updatedComment["UserId"]) {
            $updatedComment["created_by_current_user"] = true;
        }
        http_response_code(200);
        echo json_encode($updatedComment);
    }

    /**
     * delete comment using the commentsModel object based on the comment id sent in the request parameters
     * @param Request $req request for deleting comment with comment id in the request parameters
     */
    public function deleteComment($req)
    {
        $this->model->deleteComment($req->params["commentId"]);
        http_response_code(200);

    }


}