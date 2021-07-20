<?php
/**
 * The Main Controller.
 *
 * This controller will be responsible for handling user requests for page routing and cakes updates.
 *
 * @category   Controllers
 * @package    App\Controllers
 * @author     Ofer Elfassi and Dekel Ben-david
 */
include_once "src/models/MainModel.php";

/**
 * Class MainController
 */
class MainController extends BaseController
{
    /**
     * MainController constructor.
     * call parent constructor and instantiating MainModel object for database communication
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new MainModel();
    }

    /**
     * send page to user based on the data filters using the MainModel to query
     * the dataBase and using the BaseView to send the page
     * @param string $cakeFilter filter cake results
     * @param Request $req current user request object and parameters
     */
    public function init($cakeFilter = "all" ,$req =NULL)
    {
        $pageTitle = "";
        $res = false;
        if ($cakeFilter == "all") {
            $this->model->getCakes();
            $pageTitle ="Home";
        } else if ($cakeFilter == "byUser") {
            $res = $this->model->getCakes($_SESSION["user"]["id"]);
            $pageTitle ="MyCakes";
        }
        if ($cakeFilter == "byTags") {
            $res =  $this->model->getCakes(NULL,$_SESSION["user"]["tags"]);

            if(!$_SESSION["user"]["tags"]){
                $this->model->_cakes = array();
            }
            $pageTitle ="ForYou";

        }
        if ($cakeFilter == "search") {
            $res =  $this->model->getCakes(NULL,NULL,$req->params["search"]);
            $pageTitle ="Home->" .$req->params["search"];
        }

        $this->renderView('home-page',$this->model->getVars(),$pageTitle);

    }

    /**
     * send the main object page (cake) based on cake id sent by the user in the request
     * @param Request $req  current user request object and parameters
     * @param boolean $isBakingPage choose between the cake page or baking page
     */
    public function initCakePage($req,$isBakingPage=false){
        $res = false;
        $pageContent = NULL;
        if(is_numeric($req->params["cakeId"])){
            $res = $this->model->getCakes(NULL,NULL,NULL,$req->params["cakeId"]);
            $pageContent = $this->model->getVars();
        }
        if(!$res){
            $this->renderView('error-page',["error"=>""],"ERROR");
        }else if($isBakingPage){
            $this->renderView('baking-page',$pageContent,"Baking Page");
        }else{
            $this->renderView('cake-page',$pageContent,$pageContent["_cakes"][0]["name"]);
        }
    }

    /**
     * send the cake dashboard page to the user
     */
    public function initDashboardPage(){
        $this->renderView('under-construction-page',[],"Dashboard");
    }

    /**
     * send the cake maker page to the user
     */
    public function initCakeMakerPage(){
        $pageContent = $this->model->getVars();
        $this->renderView('cake-maker-page',$pageContent,"Cake Maker");
    }

    /**
     * send 404  page to the user
     */
    public function initNotFound(){
        $this->renderView('error-page',["error"=>"Not Found"],"Not Found");
    }

    /**
     * update cake data based on user specified parameters through request body
     * @param Request $req  current user request object and parameters
     */
    public function editCake($req){
        $this->model->updateCake($req->params["cakeId"],$req->body);
    }

    /**
     * delete cake based on user specified parameters through request params
     * @param Request $req  current user request object and parameters
     */
    public function deleteCake($req){
        $this->model->removeCake($req->params["cakeId"]);
    }

    /**
     * add new cake based on user specified parameters through request params and body
     * @param Request $req  current user request object and parameters
     */
    public function addCake($req){
       $newCakeId =  $this->model->insertCake($req->params["userId"],$req->body);
       echo $newCakeId;
    }

    /**
     * update cake rating based on user specified parameters through request params
     * @param Request $req  current user request object and parameters
     */
    public function rateCake($req){
        if(!isset($_SESSION["user"])){
            http_response_code(404);
        }else{
           $newRating = $this->model->updateRating($req->params["cakeId"],$_SESSION["user"]["id"],$req->params["rating"]);
            http_response_code(200);
            echo json_encode($newRating[0]);

        }
    }

    /**
     * Handle json file request
     */
    public function getJson(){
        $jsonData = file_get_contents('/public/app-style.json');
        $json = json_decode($jsonData, true);
        print_r($json);
    }

    /**
     * render html page using the BaseView object
     * @param string $viewName the view to be displayed
     * @param array $outputData parameters to be used by the view
     * @param string $title the view title
     */
    private function renderView($viewName,$outputData,$title = ""){
        $this->view->render($viewName, array_merge($outputData,["_is_loggedIn"=>$this->is_loggedIn]),$title);
    }
}