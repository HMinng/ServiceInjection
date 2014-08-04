<?php
namespace HMinng\AtsTooles;
use HMinng\ObjectGenerator\ObjectGenerator;
use Illuminate\Support\Facades\Route;

class AtsTooles extends ObjectGenerator
{
	public function process()
	{
		$urls = array();
		
		$routes =  Route::getRoutes();
		
		foreach ($routes as $route) {
			$name = $route->getName();
			if ( ! empty($name)) {
				$urls[$name] = $route->uri();
			}
			
		}
		
		return empty($urls) ? array() : $urls;
	}
}