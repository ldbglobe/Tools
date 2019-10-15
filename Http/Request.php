<?php
namespace ldbglobe\tools\Http;

class Request extends \Symfony\Component\HttpFoundation\Request {
	static function createFromGlobals()
	{
		return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
	}
}
