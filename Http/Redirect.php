<?php
namespace ldbglobe\tools\Http;

class Redirect {

	function __construct($path,$query=null)
	{
		$query = is_array($query) ? '?'.http_build_query($query):'';
		$this->path = $path;
		$this->query = $query;
	}

	function execute()
	{
		header('location:'.$this->path.$this->query,'301');
		exit();
	}
}
