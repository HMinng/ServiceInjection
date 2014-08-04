<?php 
namespace HMinng\ObjectGenerator;

class ObjectGenerator
{
	private static $instance = array();
	
	final private function __construct(){}
	
	final private function __clone(){}
	
	public static function getInstance()
	{
		$class = get_called_class();
		
		if ( ! array_key_exists($class, self::$instance) || empty(self::$instance[$class])) {
			self::$instance[$class] = new $class();
		}
		
		return self::$instance[$class];
	}
}
