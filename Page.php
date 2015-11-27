<?php
namespace ldbglobe\tools;

class Page {
	static $pageRoot = null;

	function __construct($requested_path=null)
	{
		if(!file_exists(self::$pageRoot) || !is_dir(self::$pageRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\\tools\\Page::\$pageRoot = '/var/myfolder';"
				, 1);

		$this->requested_path = $requested_path;
		// build request on construct
		$this->request = $this->buildRequest();
	}

	private function buildRequest($path = null,$param=array())
	{
		// request build only once
		if(!isset($this->request))
		{
			$path = $path!==null ? $path : $this->requested_path;

			$realpath = self::$pageRoot.'/'.$path.'.php';
			if(file_exists($realpath) && is_file($realpath))
				return (object)array('path'=>$path,'realpath'=>$realpath,'param'=>array_reverse($param));

			if(preg_match('/\/([^\/]+)$/',$path,$reg))
			{
				$param[] = $reg[1];
				$newPath = preg_replace('/\/([^\/]+)$/','',$path);
				if($newPath != $path)
					return $this->buildRequest($newPath,$param);
			}
			return false;
		}
	}

	function exist()
	{
		return $this->request!==false;
	}

	function read()
	{
		if($this->exist())
		{
			ob_start();
			require($this->request->realpath);
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
		return isset($this->request->param[$name]) ? $this->request->param[$name] : $default;
	}
}