<?php
/**
 * view  class.
 *
 * This class allows to display HTML.
 *
 * @category   View
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class BaseView
{

    /**
     * render the specified page and attach page variables
     * @param string $viewPath page path.
     * @param array $variables page variables.
     * @param string $title page title.
     */
    public function render($viewPath,$variables = array(),$title ="")
    {
        $variables+=["title"=>$title];
        extract($variables);
        $this->view = $viewPath;
        require_once ($viewPath . '.php');
    }
}