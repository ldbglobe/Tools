<?php
namespace ldbglobe\tools;

class Router {
	function __construct()
	{
		$this->routes = array();
	}

	function register($pattern,$realpath)
	{
		$this->routes[$pattern] = $realpath;
	}

	function parse($path)
	{
		echo $path;
		die();
	}
}