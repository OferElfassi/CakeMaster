<?php
/**
 * App Entry point.
 *
 * This is the entry point of the application, each user request will be redirected to this page so the router wil handle the requests.
 *
 * @category   App
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */

//define ('__BASEPATH__', '/exercises/php_rest');

//echo realpath($_SERVER["DOCUMENT_ROOT"]);

//define ('__BASEPATH__', '/');
define ('__BASEPATH__', '/students/2020-2021/web1/dev_203/');
//
include_once 'src/views/BaseView.php';
include_once 'src/controllers/BaseController.php';
include_once "src/models/BaseModel.php";
include_once 'router/Request.php';
include_once 'router/Router.php';
include_once 'src/controllers/MainController.php';
include_once 'src/controllers/AuthController.php';
include_once 'src/controllers/CommentsController.php';
include_once 'Database.php';




$db = new Database();
$router = new Router(new Request);
$controller = null;
//
session_start();
//echo "ddd";
/* assigning predefined route and actions for each rout */
//
$router->get('/', function () {

    $controller = new MainController();
    $controller->init();
});
$router->get('/getJson', function () {
    $controller = new MainController();
    $controller->getJson();
});
$router->get('/404', function () {
    $controller = new MainController();
    $controller->initNotFound();
});

$router->get('/:cakeId', function ($req) {
    $controller = new MainController();
    $controller->initCakePage($req);
});

$router->get('/Dashboard', function ($req) {
    $controller = new MainController();
    $controller->initDashboardPage();
});

$router->get('/CakeMaker', function ($req) {
    $controller = new MainController();
    $controller->initCakeMakerPage();
});
$router->get('/BakingPage/:cakeId', function ($req) {
    $controller = new MainController();
    $controller->initCakePage($req,true);
});
$router->get('/MyCakes', function () {
    $controller = new MainController();
    $controller->init("byUser");
});

$router->get('/ForYou', function () {
    $controller = new MainController();
    $controller->init("byTags");
});

$router->get('/comments/:cakeId', function ($req) {
    $controller = new CommentsController();
    $controller->getCommentsByCake($req);
});
$router->post('/addCake/:userId', function ($req) {
    $controller = new MainController();
    $controller->addCake($req);
});
$router->post('/comments', function ($req) {
    $controller = new CommentsController();
    $controller->addComment($req);
});

$router->put('/comments/:commentId', function ($req) {
    $controller = new CommentsController();
    $controller->editComment($req);
});

$router->delete('/comments/:commentId', function ($req) {
    $controller = new CommentsController();
    $controller->deleteComment($req);
});

$router->post('/updateCake/:cakeId', function ($req) {
        $controller = new MainController();
        $controller->editCake($req);
});

$router->delete('/delete/:cakeId', function ($req) {
        $controller = new MainController();
        $controller->deleteCake($req);
});

$router->get('/rate/:cakeId/:rating', function ($req) {
    $controller = new MainController();
    $controller->rateCake($req);
});

$router->get('//:search', function ($req) {
    $controller = new MainController();
    $controller->init("search",$req);
});

$router->get('/auth', function () {
    $controller = new AuthController();
    $controller->init();
});

$router->get('/signup', function () {
    $controller = new AuthController();
    $controller->init();
});

$router->post('/auth/login', function ($req) {
    $controller = new AuthController();
    $controller->login($req);
});

$router->post('/auth/signup', function ($req) {
    $controller = new AuthController();
    $controller->signup($req);
});

$router->get('/auth/:username', function ($req) {
    $controller = new AuthController();
    $controller->checkUsername($req);
});

$router->get('/auth/logout', function ($req) {
    $controller = new AuthController();
    $controller->logout($req);
});

$router->get('/auth/:nickname', function ($req) {
    $controller = new AuthController();
    $controller->checkNickname($req);
});

$router->post('/auth/updateinfo', function ($req) {
    $controller = new AuthController();
    $controller->updateInfo($req);
});

$router->delete('/auth/:userid', function ($req) {
    $controller = new AuthController();
    $controller->deleteUser($req);
});
