<?php
namespace ldbglobe\tools;

use ldbglobe\tools\PageComponentCapture;

class PageComponent {
	static $componentRoot = null;

	function __construct($name=null)
	{
		if(!file_exists(self::$componentRoot) || !is_dir(self::$componentRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\\tools\\PageComponent::\$componentRoot = '/var/myfolder';"
				, 1);

		$this->name = $name;
		$this->vars = array();
	}

	function getPath()
	{
		return self::$componentRoot.'/'.$this->name.'.php';
	}

	function read()
	{
		if(file_exists($this->getPath()))
		{
			return require($this->getPath());
		}
		return false;
	}
	function flush()
	{
		$component = $this->read();
		if($component)
			echo $component->data;
	}

	function set($name,$value)
	{
		$this->vars[$name] = $value;
		return $this;
	}
	function remove($name)
	{
		unset($this->vars[$name]);
		return $this;
	}
	function get($name,$default=null)
	{
		return isset($this->vars[$name]) ? $this->vars[$name] : $default;
	}

	function hash()
	{
		return sha1($this->name.serialize($this->vars));
	}

	static function Capture()
	{
		return new PageComponentCapture();
	}
}

class PageComponentCapture {
	function __construct()
	{
		ob_start();
	}

	function end()
	{
		return (object)array('data'=>ob_get_clean());
	}
}