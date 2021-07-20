<?php
/**
 * The Auth Controller.
 *
 * This controller will be responsible for handling user authentication requests.
 *
 * @category   Controllers
 * @package    App\Controllers
 * @author     Ofer Elfassi and Dekel Ben-david
 */

include_once "src/models/AuthModel.php";

/**
 * Class AuthController
 */
class AuthController extends BaseController
{
    /**
     * AuthController constructor.
     * call parent constructor and instantiating AuthModel object for database communication
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AuthModel();
    }

    /**
     * send the auth page to the user using the BaseModel and querying tag names from database for the signup process using the AuthModel
     */
    public function init()
    {

        $tags = $this->model->get($this->model->tables["tags"]);
        $this->view->render('auth-page',["tags"=>$tags]);
    }

    /**
     * attempt user login by querying the database and search for username and password that matched the user request
     * @param Request $req
     */
    public function login($req)
    {
        if($this->model->exist($this->model->tables["users"],[["colName"=>"username","value"=>$req->body["username"]]])){
            $result =  $this->model->getUser($req->body["username"],$req->body["password"]);
            if(!count($result)){
                /* wrong password */
                http_response_code(401);
            }else{
                /* login success */
                $_SESSION["user"] = $result;
                http_response_code(200);
                header('Content-type: application/json');
                echo json_encode($result);
            }
        }else{
            /* username not exist */
            http_response_code(404);
        }
    }

    /**
     * signing up user by add the user data sent in the request body to the database using the AuthModel object
     * @param Request $req
     */
    public function signup($req)
    {
       $newUserId = $this->model->addUser($_POST);
       if($newUserId){
           http_response_code(200);
           echo json_encode($newUserId);
       }else{
           http_response_code(400);
       }
    }

    /**
     * preform user logout by clearing the session data and redirecting the user to home page
     * @param Request $req logout request
     */
    public function logout($req){
        unset($_SESSION["user"]);
        header("Location:".__BASEPATH__);
    }

    /**
     * update user personal info sent by the request
     * @param Request $req update info request
     */
    public function updateInfo($req){
        if(isset($req->body["nickname"])){
            /* update nickname and full name */
            $this->model->updateUser($_SESSION["user"]["id"],$req->body);
            http_response_code(200);
        }else if(isset($req->body["password"])){
            /* change password */
            $this->model->updateUser($_SESSION["user"]["id"],["password"=>$req->body["password"]]);
            http_response_code(200);
            echo "ok";
        }else if(isset($req->body["tags"])){
            /* update preferences */
            $this->model->updateUserTags($_SESSION["user"]["id"],$req->body["tags"]);
            http_response_code(200);
        }else{
            /* updates attempts failed */
            http_response_code(400);
        }
        $result =  $this->model->getUser($_SESSION["user"]["username"],$_SESSION["user"]["password"]);
        unset($_SESSION["user"]);
        $_SESSION["user"] = $result;
    }

    /**
     * validating unique username
     * @param Request $req request with username parameter
     */
    public function checkUsername($req){
        $result = !$this->model->exist($this->model->tables["users"],[["colName"=>"username","value"=>$req->params["username"]]]);
        header('Content-type: application/json');
        echo json_encode($result);
    }

    /**
     * validating unique nickname
     * @param Request $req request with nickname parameter
     */
    public function checkNickname($req){
        $result = !$this->model->exist($this->model->tables["users"],[["colName"=>"nickname","value"=>$req->params["nickname"]]]);
        header('Content-type: application/json');
        echo json_encode($result);
    }

    /**
     * delete user based on user id in the request parameters
     * @param Request $req request with user id parameter
     */
    public function deleteUser($req){
        $this->model->removeUser($req->params["userid"]);
        unset($_SESSION["user"]);
    }
}