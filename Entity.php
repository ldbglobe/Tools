<?php
namespace ldbglobe\tools;

class Entity extends \Model {

	static private $__settings = array(
		'id_mode'=>'autoinc', //[autoinc, UUID]
	);

	static function Settings($k,$v)
	{
		if(isset(self::$__settings[$k]))
		{
			self::$__settings[$k] = $v;
		}
		else
		{
			throw new Exception("Entity settings \"$k\" does not exist", 1);
		}
	}

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
	static function factory($class_name=null, $connection_name = NULL) // return ORM wrapper instance to make querying through idiorm
	{
		return \Model::factory($class_name ? $class_name:get_called_class(), $connection_name);
	}

	function save()
	{
		if(self::$__settings['id_mode']==='UUID')
		{
			if(!isset($this->id) || empty($this->id))
			{
				$this->id = Ramsey\Uuid\Uuid::uuid4();
			}
		}
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