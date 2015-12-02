<?php
namespace ldbglobe\tools;

class Page {
	static $pageRoot = null;

	function __construct($route,$request)
	{
		if(!file_exists(self::$pageRoot) || !is_dir(self::$pageRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\\tools\\Page::\$pageRoot = '/var/myfolder';"
				, 1);

		$this->route = $route;
		$this->request = $request;
	}

	function exist()
	{
		return file_exists(self::$pageRoot.'/'.$this->route->_page.'.php');
	}

	function read()
	{
		if($this->exist())
		{
			ob_start();
			require(self::$pageRoot.'/'.$this->route->_page.'.php');
			return ob_get_clean();
		}
		return false;
	}
	function flush()
	{
		echo $this->read();
	}

	function get($name,$default=null)
	{
		return isset($this->route->{$name}) ? $this->route->{$name} : $default;
	}

	function component($componentName)
	{
		return new PageComponent($componentName);
	}
}