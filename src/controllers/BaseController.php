<?php
/**
 * Controller class.
 *
 * This class represents the abstract controller.
 *
 * @category   ControllerS
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class BaseController
{
    protected $view;
    protected $model;
    protected $is_loggedIn = false;

    /**
     * BaseController constructor.
     * check if there is an active logged in user session
     * and instantiating new baseView object for handling php pages rendering
     */
    public function __construct()
    {
        $this->is_loggedIn = isset($_SESSION["user"]);
        $this->view = new BaseView();

    }
}