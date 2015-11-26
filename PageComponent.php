<?php
namespace ldbglobe;

class PageComponent {
	static $componentRoot = null;

	function __construct($componentName=null)
	{
		if(!file_exists(self::$componentRoot) || !is_dir(self::$componentRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\PageComponent::\$componentRoot = '/var/myfolder';"
				, 1);

		$this->componentName = $componentName;
	}

	function getPath()
	{
		return self::$componentRoot.'/'.$this->componentName.'.php';
	}

	function read($vars=null)
	{
		if(file_exists($this->getPath()))
		{
			if(is_array($vars))
				extract($vars);
			return require($this->getPath());
		}
		return false;
	}
	function flush($vars=null)
	{
		$component = $this->read($vars);
		if($component)
			echo $component->data;
	}

	static function Capture()
	{
		return new \ldbglobe\PageComponentCapture();
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