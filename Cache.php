<?php
namespace ldbglobe\tools;

class Cache {
	static $storageFolder = null;
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

	static function ExternalToStorage($url,$ttl, $storage=null)
	{
		$cache = new self('loadUrl'.$url,$ttl,$storage);
		if(!$cache->isUpToDate())
			$cache->captureUrl($url);
		return $cache;
	}

	function getPath($basepath=null)
	{
		$basepath = $basepath!==NULL ? $basepath.'/' : self::$storageRoot.'/';
		return $basepath.$this->storage.'/'.$this->uid;
	}

	function timeLeft() {
		if(!self::$forceUpdate && file_exists($this->getPath()))
		{
			$data = unserialize(file_get_contents($this->getPath()));
			return $data['creation_time'] - (time()-$this->ttl);
		}
	}

	function exists()
	{
		return file_exists($this->getPath());
	}

	function isUpToDate()
	{
		return $this->timeLeft() > 0;
	}

	function captureUrl($url)
	{
		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET',$url,['http_errors'=>false]);
		if($response->getStatusCode() == 200)
			$this->write((string)$response->getBody());
	}

	function captureStart()
	{
		ob_start();
	}

	function captureEnd()
	{
		$this->write(ob_get_clean());
	}

	function write($content,$invalidate=false)
	{
		$time = $invalidate ? 0:time();
		@mkdir(dirname($this->getPath()),0777,true);
		return file_put_contents($this->getPath(),serialize(array(
			'creation_time'=>$time,
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

	function touch()
	{
		touch($this->getPath());
	}

	function invalidate()
	{
		$this->write($this->read(),true);
	}

	function flush()
	{
		echo $this->read();
	}
}