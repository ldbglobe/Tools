<?php
namespace ldbglobe\tools;

class Cache {
	static $storageRoot = null;
	static $storageDefault = null;
	static $forceUpdate = false;

	function __construct($uid, $ttl, $storage=null)
	{
		if(!file_exists(self::$storageRoot) || !is_dir(self::$storageRoot))
			throw new \Exception(
"Invalid storage directory
Settings samples :
\\ldbglobe\\tools\\Cache::\$storageRoot = '/var/myfolder/storage';
\\ldbglobe\\tools\\Cache::\$storageDefault = 'cache'; // default cache folder name
\\ldbglobe\\tools\\Cache::\$forceUpdate = false; // set to true deactivate cache handling"
				, 1);

		$storage = $storage!==null ? $storage : self::$storageDefault;
		$this->storage = $storage;
		$this->uid = preg_replace('/^(..)(..)/','\\1/\\2/',sha1($uid));
		$this->ttl = $ttl;
	}

	function getPath()
	{
		return self::$storageRoot.'/'.$this->storage.'/'.$this->uid;
	}

	function timeLeft() {
		if(!self::$forceUpdate && file_exists($this->getPath()))
		{
			$data = unserialize(file_get_contents($this->getPath()));
			return $data['creation_time'] - (time()-$this->ttl);
		}
	}

	function isUpToDate()
	{
		return $this->timeLeft() > 0;
	}

	function captureStart()
	{
		ob_start();
	}

	function captureEnd()
	{
		$this->write(ob_get_clean());
	}

	function write($content)
	{
		@mkdir(dirname($this->getPath()),0777,true);
		return file_put_contents($this->getPath(),serialize(array(
			'creation_time'=>time(),
			'content'=>$content,
			)));
	}

	function read()
	{
		if(file_exists($this->getPath()))
		{
			$data = unserialize(file_get_contents($this->getPath()));
			return $data['content'];
		}
	}

	function flush()
	{
		echo $this->read();
	}
}