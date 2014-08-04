<?php
namespace HMinng\AtsShell;
use HMinng\ObjectGenerator\ObjectGenerator;
use HMinng\AtsHttp\AtsHttp;
use HMinng\AtsConfig\AtsConfig;

class AtsShell extends ObjectGenerator
{
	private $method = '/api/list';
	
	public function exec()
	{
		try {
			$urls = $this->getUrls();
			
			$path = $this->createDir();
			
			$this->createDict($path, $urls);
		
			return true;
		} catch (\Exception $e) {
			echo $e->getMessage();exit;
		}
	}
	
	private function getUrls()
	{
		return AtsConfig::getInstance()->getNode('urls');
	}
	
	private function createDir()
	{
		$path = storage_path() . DIRECTORY_SEPARATOR . 'dict';
		
		if ( ! is_dir($path)) {
			@mkdir($path, 0777);
		}

		return $path;
	}
	
	private function createDict($path, $urls)
	{
		foreach ($urls as $key => $value)
		{
			$file = $path . DIRECTORY_SEPARATOR . $key . '.php';
			
			$data = json_decode(AtsHttp::getInstance()->send($value . $this->method));
			
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
		}
		
		return true;
	}
}

AtsShell::getInstance()->exec();