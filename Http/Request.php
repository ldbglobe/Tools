<?php
namespace ldbglobe\tools\Http;

class Request {

	static $_request = null;

	static private function __init()
	{
		if(self::$_request===null)
		{
			self::$_request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
		}
	}
	public function getBaseUrl()
	{
		self::_init();
		if(self::$_request)
			return self::$_request->getBaseUrl();
	}
	public function getPathInfo()
	{
		self::_init();
		if(self::$_request)
			return self::$_request->getPathInfo();
	}
	public function getQueryString()
	{
		self::_init();
		if(self::$_request)
			return self::$_request->getQueryString();
	}
	public function get($k,$default=null)
	{
		self::_init();
		if(self::$_request)
			return self::$_request->get($k,$default);
		return $default;
	}
}
