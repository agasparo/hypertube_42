<?php

class Router {

    /**
     * @url String
     */
    private $url;

    /**
     * @routes Array
     */
    private $routes = [];

    public function __Construct(String $url) {
        $this->url = $url;
    }


    public function get(String $path, String $callable) {
    	$route = new Route($path, $callable);
    	$this->routes['GET'][] = $route;
    }

    public function post(String $path, String $callable) {
    	$route = new Route($path, $callable);
    	$this->routes['POST'][] = $route;
    }

    public function run() {

        $controler = new controler();

    	if (!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
    		return ($controler->not_found());
    	foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
    		if ($route->match($this->url)) {
    			return ($route->call());
    		}
    	}
    	return ($controler->not_found());

    }
}

?>