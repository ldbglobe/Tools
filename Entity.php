<?php
namespace ldbglobe\tools;

class Entity extends \Model {

	static function factory()
	{
		return \Model::factory(get_called_class());
	}
}