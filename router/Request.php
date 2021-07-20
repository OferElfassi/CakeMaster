<?php
/**
 * Request class.
 *
 * This class stores parameters from http requests.
 *
 * @category   Request
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class Request
{
    public $type;
    public $endpoint;
    public $hasParams;
    public $body;
    public $params;

    /**
     * Request constructor.
     * parse the current http request and extracting the request params body and type
     */
    function __construct()
    {
        $this->type = $_SERVER['REQUEST_METHOD'];
        $this->endpoint = $this->getEndpoint();
        $this->hasParams = (false !== strpos($_SERVER['REQUEST_URI'], '?'));
        $this->params = array();
        $this->body = array();

        if ($this->type == "GET" || $this->hasParams) {
            foreach ($_GET as $key => $value) {
                $this->params[$key] = $value;
            }
        }
        if ($this->type == "POST" ) {
            foreach ($_POST as $key => $value) {
                $this->body[$key] = $value;
            }
        }
        if ($this->type == "PUT" ) {
            $put_vars = NULL;
            parse_str(file_get_contents("php://input"),$put_vars);
            foreach ($put_vars as $key => $value) {
                $this->body[$key] = $value;
            }
        }
    }

    /**
     * strip the request url and extract parameters
     * @return false|string
     */
    private function getEndpoint()
    {
        $fullPath = $_SERVER['REQUEST_URI'];
        $cleanPath = substr($fullPath,strpos($fullPath,__BASEPATH__)+strlen(__BASEPATH__));

        if (strpos($cleanPath, "?") !== false) {
            return $this->formatParams($cleanPath);
        }
        return $cleanPath;
    }

    /**
     * format the parameter to expressjs style parameters url (?key=value TO /:value style)
     * @param string $cleanPath
     * @return false|string
     */
    private function formatParams($cleanPath){
        $pathWithoutParams = substr($cleanPath, 0, strpos($cleanPath, "?"));
        foreach ($_GET as $key => $value) {
            $pathWithoutParams .="/:".$key;
        }
        return $pathWithoutParams;
    }

}