<?php

class Route {

    /**
     * @path String
     */
	private $path;

    /**
     * @callable String
     */
	private $callable;

    /**
     * @matches Array
     */
	private $matches;

	public function __Construct(String $path, String $callable) {
    	$this->path = trim($path, '/');
    	$this->callable = $callable;
    }

    public function match(String $url) : Int {
    	$url = trim($url, '/');
    	$path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
    	$regex = "#^$path$#";
    	if (!preg_match($regex, $url, $matches))
    		return (0);
    	array_shift($matches);
    	$this->matches = $matches;
    	return (1);
    }

    public function call() {
        if (is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controler = new controler();
            return (call_user_func_array([$controler, $params[1]], $this->matches));
        } else {
    	   return (call_user_func_array($this->callable, $this->matches));
        }
    }
}

?>