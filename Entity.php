<?php
namespace ldbglobe\tools;

class Entity extends \Model {

	public static $_id_column = 'id';
	public static $_id_mode = 'autoinc';

	// shortcut to manipulate paris model

	static function create($connection_name = NULL) // make a new ORM model instance ready to use and call onCreate hook
	{
		return \Model::factory(get_called_class(), $connection_name)->create()->onCreate();
	}
	static function load($id, $connection_name = NULL) // load an ORM instance from BDD and call onLoad hook
	{
		$entity = \Model::factory(get_called_class(), $connection_name)->where('id',$id)->find_one();
		return $entity ? $entity->onLoad() : false;
	}
	static function factory($class_name=null, $connection_name = NULL) // return ORM wrapper instance to make querying through idiorm
	{
		return \Model::factory($class_name ? $class_name:get_called_class(), $connection_name);
	}

	// event hook (to override in your own entity extended classes)

	function onCreate()
	{
		if(self::$_id_mode==='UUID')
		{
			$this->id = (string)\Ramsey\Uuid\Uuid::uuid4();
		}

		// override this function in your entity declaration
		return $this;
	}
	function onLoad()
	{
		// override this function in your entity declaration
		return $this;
	}
}