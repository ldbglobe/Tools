<?php
namespace ldbglobe\tools;

use ldbglobe\tools\PageComponentCapture;

class PageComponent {
	static $stats = array(
		'instance'=>0,
		'global'=>0,
		'time'=>array(),
	);
	static $componentRoot = null;

	function __construct($name, $request)
	{
		self::$stats['instance']++;
		$this->_timer = microtime(true);

		if(!file_exists(self::$componentRoot) || !is_dir(self::$componentRoot))
			throw new \Exception(
"Invalid component directory
Settings samples :
\\ldbglobe\\tools\\PageComponent::\$componentRoot = '/var/myfolder';"
				, 1);

		$this->request = $request;
		$this->name = $name;
		$this->vars = array();
	}

	function getPath()
	{
		return self::$componentRoot.'/'.$this->name.'.php';
	}

	function exist()
	{
		return file_exists($this->getPath());
	}
	function read()
	{
		if($this->exist())
		{
			ob_start();
			require($this->getPath());
			$this->stats();
			return ob_get_clean();
		}
		return false;
	}
	function readReturn()
	{
		if($this->exist())
		{
			return require($this->getPath());
		}
		return false;
	}
	function readBoth()
	{
		if($this->exist())
		{
			ob_start();
			$return = require($this->getPath());
			$display = ob_get_clean();
			return (object)array('return'=>$return,'display'=>$display);
		}
		return false;
	}
	function json()
	{
		return json_decode($this->read());
	}
	function flush()
	{
		$output = $this->read();
		if($output!==false)
			echo $output;
		else
			echo 'Component '.$this->name.' does not exist';
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

	function component($componentName)
	{
		return new PageComponent($componentName, $this->request);
	}

	function stats()
	{
		self::$stats['global'] += microtime(true) - $this->_timer;
		if(!isset(self::$stats['time'][$this->name]))
			self::$stats['time'][$this->name] = 0;
		self::$stats['time'][$this->name] += microtime(true) - $this->_timer;
		$this->_timer = microtime(true);
	}
}