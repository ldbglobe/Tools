<?php
namespace ldbglobe\tools;

class Entity extends \Model {

	// shortcut to manipulate paris model

	static function create() // make a new ORM model instance ready to use and call onCreate hook
	{
		return \Model::factory(get_called_class())->create()->onCreate();
	}
	static function load($id) // load an ORM instance from BDD and call onLoad hook
	{
		$entity = \Model::factory(get_called_class())->where('id',$id)->find_one();
		return $entity ? $entity->onLoad() : false;
	}
	static function factory() // return ORM wrapper instance to make querying through idiorm
	{
		return \Model::factory(get_called_class());
	}

	// event hook (to override in your own entity extended classes)

	function onCreate()
	{
		// override this function in your entity declaration
		return $this;
	}
	function onLoad()
	{
		// override this function in your entity declaration
		return $this;
	}
}