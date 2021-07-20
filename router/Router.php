<?php
/**
 * Router class.
 *
 * store predefined routes and match routes to requests,  on route request match -> execute provided function
 * @category   Router
 * @package    Core
 * @author     Ofer Elfassi and Dekel Ben-david
 */
class Router
{
    private $request;

    /**
     * Router constructor.
     * store current http request
     * @param Request $request current http request
     */
    function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * this function will be called for every predefined route,
     * the function names are the http methode (get,post,delete...)
     * for each http methode the function will store hashtable where the hash table name is the http method name
     * the keys are the http route and the values are the predefined method to execute when there is match
     * for example : get:{"/getUser":getUser(),"/getCake":getCake()}
     * @param string $name function name
     * @param array $args specified predefined rout and method to execute on match
     */
    function __call($name, $args)
    {
        list($route, $method) = $args;
        if ($this->isMatchParams($route)) {
            $this->{strtolower($name)}[$route] = $method;
        } else {
            $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
        }
    }

    /**
     * check if the predefined route parameters is the same as the current request parameters
     * @param string $route predefind route
     * @return bool
     */
    private function isMatchParams($route)
    {
        if ((strpos($route, ":") !== false)&& $this->request->hasParams) {
            $targetParams = explode("/:", substr($route, strpos($route, ":")));
            $targetParams[0] = str_replace(":", "", $targetParams[0]);
            return $this->keys_are_equal($this->request->params, array_flip($targetParams));
        }
        return false;
    }

    /**
     * helper methode to compare tow arrays by keys
     * @param $array1
     * @param $array2
     * @return bool
     */
    private function keys_are_equal($array1, $array2)
    {
        return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
    }

    /**
     * Removes trailing forward slashes from the the route.
     * @param string $route
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    /**
     * unknown request handler
     */
    private function defaultRequestHandler()
    {
        header("Location:".__BASEPATH__."/404");
    }

    /**
     * Resolves route
     * check if there is match between current request route and one of the predefined routes
     * and execute the predefined methode or calling defaultRequestHandler for unmatched routes
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->type)};

        $formatedRoute = $this->formatRoute($this->request->endpoint);
        $method = $methodDictionary[$formatedRoute];

        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }


         call_user_func_array($method, array($this->request));
    }

    /**
     * Router destructor
     * after all predefined routes has been stored, this will call resolve to find match
     */
    function __destruct()
    {
        $this->resolve();
    }
}