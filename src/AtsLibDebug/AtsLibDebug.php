<?php
namespace HMinng\AtsLibDebug;
use HMinng\ObjectGenerator\ObjectGenerator;
use HMinng\AtsHttp\AtsHttp;

class AtsLibDebug extends ObjectGenerator
{
	private $urls = array();
	
	private $method = '/api/list';
	
	public function getUrl($method, $toId)
	{
		$this->urls = require app_path() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'ip.php';
		
		$urls = $this->check($method, $toId);
		
		return $urls ? $this->urls[$toId] . '/' . $urls[$method] : false;
	}
	
	private function check($method, $toId)
	{
		$path = storage_path() . DIRECTORY_SEPARATOR . 'dict';
		
		if ( ! is_dir($path)) {
			@mkdir($path, 0777);
		}
		
		$file = $path . DIRECTORY_SEPARATOR . $toId . '.php';
		
		$urls = array();
		
		if (is_file($file)) {
			$urls = require $file;
		} 
		
		if ( ! is_file($file) || ! is_array($urls) || empty($urls) || ! array_key_exists($method, $urls) ) {
			$flag = $this->fetch($toId, $file);
			
			if ( ! $flag) {
				return false;
			}
			
			$urls = require $file;
		}
		
		if ( ! is_array($urls) || ! array_key_exists($method, $urls) ) {
			return false;
		}
		
		return $urls;
	}
	
	private function fetch($toId, $file)
	{
		$data = json_decode(AtsHttp::getInstance()->send($this->urls[$toId] . $this->method));
		
		if ( ! $data) {
			throw new \Exception('缓存文件生成失败');
		}
		
		$urls = "<?php \r\n return array(\r\n";
		
		$count = count($data);
		
		if ( ! $count) {
			return false;
		}
		
		foreach ($data as $key => $value) {
			$urls .= '\'' .$key . '\' => \''. $value . "',\r\n";
		}
		
		$urls .= ');';
		
		file_put_contents($file, $urls);
		
		return true;
	}
}