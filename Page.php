<?php
namespace ldbglobe\tools;

class Page {
	static $pageRoot = null;

	function __construct($pageName=null)
	{
		if(!file_exists(self::$pageRoot) || !is_dir(self::$pageRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\\tools\\Page::\$pageRoot = '/var/myfolder';"
				, 1);

		$this->pageName = $pageName;
	}

	function getPath()
	{
		return self::$pageRoot.'/'.$this->pageName.'.php';
	}

	function read($vars=null)
	{
		if(file_exists($this->getPath()))
		{
			ob_start();
			if(is_array($vars))
				extract($vars);
			require($this->getPath());
			return ob_get_clean();
		}
		return false;
	}
	function flush($vars=null)
	{
		echo $this->read($vars);
	}
}