<?php
/**
 * The Auth Model class.
 *
 * This model will be responsible for database communication to execute queries for user authentication (signup,login,update info).
 *
 * @category   Models
 * @package    App\Models
 * @author     Ofer Elfassi and Dekel Ben-david
 */

include_once "src/models/AuthModel.php";
/**
 * Class AuthModel
 */
class AuthModel extends BaseModel {
    protected $user ;
    protected $_table_name = 'users';

    /**
     * AuthModel constructor.
     * calling the parent constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get user based on username and password (login process)
     * @param string $username username
     * @param string $password password
     * @return array
     */
    public function getUser ($username,$password){
        $tags = array();
       $user = $this->get($this->tables["users"],NULL,NULL,false,"*",NULL,NULL,
           ["username"=>$username,"password"=>$password]);
       if(count($user)) {
           $tags = $this->get($this->tables["cake_user_tag"], "UserId", $user[0]["id"]);
           return array_merge($user[0], ["tags" => $tags]);
       }
       return $user;
    }

    /**
     * add new user to database (signup process)
     * @param  array $userdata new user data
     * @return string
     */
    public function addUser ($userdata){
     $userId =   $this->create($this->tables["users"],[
            "username"=>$userdata["username"],
            "password"=>$userdata["password"],
            "fullname"=>$userdata["fullname"],
            "nickname"=>$userdata["nickname"]
            ]
        );
     if($userId) {
         foreach ($userdata["tags"] as $tagId) {
             $this->create($this->tables["cake_user_tag"], [
                     "TagId" => $tagId,
                     "UserId" => $userId
                 ]
             );
         }
     }
    return $userId;
    }

    /**
     * update user data
     * @param numeric $userId the id of the user to be updated
     * @param array $dataArr updated user data
     */
    public function updateUser($userId,$dataArr){
        $this->edit($this->tables["users"],$dataArr,$userId);
    }

    /**
     * update user tags (preferences update process)
     * @param numeric $userId the user id to update tag for
     * @param array $tags updated tags list
     */
    public function updateUserTags($userId,$tags){
        $this->delete($this->tables["cake_user_tag"],"UserId",$userId);
        foreach ($tags as $tagId) {
            $this->create($this->tables["cake_user_tag"], [
                    "TagId" => $tagId,
                    "UserId" => $userId
                ]
            );
        }
    }

    /**
     * delete user from database by user id
     * @param numeric $userid the id of the user to be deleted
     */
    public function removeUser($userid)
    {
        $this->delete($this->tables["users"],"id",$userid,true);
    }



}