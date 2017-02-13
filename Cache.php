<?php
namespace ldbglobe\tools;

class Cache {
	static $stats = array(
		'instance'=>0,
		'global'=>0,
		'time'=>array(
			'write'=>0,
			'read'=>0,
			'touch'=>0,
			),
		);
	static $storageFolder = null;
	static $storageRoot = null;
	static $storageDefault = null;
	static $forceUpdate = false;

	function __construct($uid, $ttl, $storage=null, $ext=null)
	{
		self::$stats['instance']++;
		$this->_timer = microtime(true);

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
		$this->ext = $ext;
	}

	static function Purge($storage,$ttl)
	{
		$response = (object)array(
			'deleted'=>0,
			'remaining'=>0,
		);
		$basedir = self::$storageRoot.'/'.$storage;
		if(file_exists($basedir) && is_dir($basedir))
		{
			$l0 = opendir($basedir);
			while($d0 = readdir($l0))
			{
				if($d0!='.' && $d0!='..' && is_dir($basedir.'/'.$d0))
				{
					$l1 = opendir($basedir.'/'.$d0);
					while($d1 = readdir($l1))
					{
						if($d1!='.' && $d1!='..' && is_dir($basedir.'/'.$d0.'/'.$d1))
						{
							$l2 = opendir($basedir.'/'.$d0.'/'.$d1);
							while($d2 = readdir($l2))
							{
								if($d2!='.' && $d2!='..' && is_file($basedir.'/'.$d0.'/'.$d1.'/'.$d2))
								{
									if(filemtime($basedir.'/'.$d0.'/'.$d1.'/'.$d2) < time() - $ttl)
									{
										$response->deleted++;
										@unlink(realpath($basedir.'/'.$d0.'/'.$d1.'/'.$d2));
									}
									else
									{
										$response->remaining++;
									}
								}
							}
							@rmdir(realpath($basedir.'/'.$d0.'/'.$d1.'/'));
						}
					}
					@rmdir(realpath($basedir.'/'.$d0.'/'));
				}
			}
		}
		return $response;
	}

	static function ExternalToStorage($url,$ttl, $storage=null,$ext=null)
	{
		$cache = new self('loadUrl'.$url,$ttl,$storage,$ext);
		if(!empty($url) && !$cache->isUpToDate())
				$cache->captureUrl($url);
		return $cache;
	}

	function getPath($basepath=null)
	{
		$basepath = $basepath!==NULL ? $basepath.'/' : self::$storageRoot.'/';
		return $basepath.$this->storage.'/'.$this->uid.($this->ext ? '.'.$this->ext:'');
	}

	function timeLeft() {
		if(!self::$forceUpdate && file_exists($this->getPath()))
		{
			$data = unserialize(file_get_contents($this->getPath()));
			return $data['creation_time'] - (time()-$this->ttl);
		}
		return -1;
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
		$response = $client->request('GET',$url,['verify'=>false, 'http_errors'=>false]);
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
		$r = file_put_contents($this->getPath(),serialize(array(
			'creation_time'=>$time,
			'content'=>$content,
			)));
		$this->stats('write');
		return $r;
	}

	function read()
	{
		$r = null;
		if(file_exists($this->getPath()))
		{
			$data = unserialize(file_get_contents($this->getPath()));
			$r = $data['content'];
		}
		$this->stats('read');
		return $r;
	}

	function touch()
	{
		touch($this->getPath());
		$this->stats('touch');
	}

	function invalidate()
	{
		$this->write($this->read(),true);
	}

	function flush()
	{
		echo $this->read();
	}

	function stats($operation=null)
	{
		self::$stats['global'] += microtime(true) - $this->_timer;
		if($operation && isset(self::$stats['time'][$operation]))
			self::$stats['time'][$operation] += microtime(true) - $this->_timer;
		$this->_timer = microtime(true);
	}
}