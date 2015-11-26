<?php
namespace ldbglobe\Http\tools;

class Redirect {

	function __construct($path,$query=null)
	{
		$query = is_array($query) ? '?'.http_build_query($query):'';
		$this->path = $path;
		$this->query = $query;
	}

	function execute()
	{
		header('location:'.BASEPATH.$this->path.$this->query);
		exit();
	}
}
