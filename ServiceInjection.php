<?php 
use HMinng\AtsLog\AtsLog;
use HMinng\AtsResponse\AtsResponse;
use HMinng\AtsEncryption\AtsEncryption;
use HMinng\AtsHttp\AtsHttp;
use HMinng\AtsSystem\AtsSystem;
use HMinng\AtsCode\AtsCode;
use HMinng\AtsLib\AtsLib;
use HMinng\AtsLibDebug\AtsLibDebug;
use HMinng\AtsTooles\AtsTooles;

class ServiceInjection
{
	public static function send($traceId, $fromId, $toId, $method, $params, $status = 1, $category = 1)
	{
		AtsLog::getInstance()->startTime = microtime(true);
        
		if (array_key_exists('ATS_ENV_TAG', $_SERVER) && $_SERVER['ATS_ENV_TAG'] == 'online') {
			$url = AtsLib::getInstance()->getUrl($method, $toId);
		} else {
			$url = AtsLibDebug::getInstance()->getUrl($method, $toId);
		}
		
		if ( ! $url) {
			AtsResponse::getInstance()->failure($traceId, $fromId, $toId, AtsCode::INVOKE_URL_UNDEFIND, $params, $method);
		}
	  
		switch ($category) {
			case 1:
				$result = AtsHttp::getInstance()->send($url, $params);
				break;
			case 2:
				$params = AtsEncryption::getInstance()->encryption($params, 'ENCODE');
				$result = AtsHttp::getInstance()->send($url, array('params' => $params));
				break;
			case 3:
				$params = AtsEncryption::getInstance()->encryption($params);
				$result = AtsHttp::getInstance()->send($url, array('params' => $params));
				break;
			default:
				$result = false;
		}
		
		if ( ! $result) {
			AtsResponse::getInstance()->failure($traceId, $fromId, $toId, AtsCode::CREATE_CURL_FAILURE, $params, $url);;
		}
		
		AtsLog::getInstance()->write($traceId, $fromId, $toId, $result, $params, $url);
		
		return json_decode($result, true);
	}
	
	public static function authcode($params, $category)
	{
		if ($category == 2) {			
			$category = 'DECODE';
		} else if ($category ==  3) {
			$category = 'SIGNATURE';
		}
		
		/* @var $atsEncryption AtsEncryption */
		$atsEncryption = AtsEncryption::getInstance();
		return $atsEncryption->encryption($params, $category);
	}
	
	public static function system($name, $status = false)
	{
		if (empty($name)) {
			throw new Exception(AtsCode::getInstance()->getMessage(AtsCode::PARAMS_ERROR), AtsCode::PARAMS_ERROR);
		}
		
		$name = strtolower($name);
				
		/* @var $atsSystem AtsSystem */
		$atsSystem = AtsSystem::getInstance();
		$names = $atsSystem->getClassNames();
		
		$id = 0;
		
		foreach ($names as $key => $value) {
			$value = strtolower($value);
			
			if ($name == $value) {
				$id = $key;
			}
		}
		
		if ($status) {
			return $id;
		}
		
		return $atsSystem->getClassNameById($id);
	}
	
	public static function getUrls() 
	{
		$urls = AtsTooles::getInstance()->process();
		
		return json_encode($urls);
	}
	
	final public static function autoload($class)
	{
		$count = substr_count($class, '\\');
		
		if ($count < 1) {
			return true;
		}
		
		$class = explode('\\', $class);
		
		if ($class[0] == 'HMinng') {
			unset($class[0]);
			
			$classPath = dirname(__FILE__);
			
			foreach ($class as $value) {
				$classPath  .= DIRECTORY_SEPARATOR . $value; 
			}
			
			$classPath .= '.php';
			
			require_once $classPath;
		}
		
		return true;
	}
}

spl_autoload_register(array('ServiceInjection', 'autoload'));
