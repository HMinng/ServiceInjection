<?php 
namespace HMinng\AtsHttp;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsHttp extends ObjectGenerator
{
	public function send($url, $params = array())
	{
		return $this->Http($url, $params);
	}
	
	private function http($url, $data)
	{
		$ch = curl_init();
		
		if ( ! $ch) {
			return false;
		}
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$result = curl_exec($ch);
		
		if (!$result) {
			$error = curl_error($ch) . ' <'. $url .'>';
			
			throw new \Exception($error);
		}
		
		curl_close($ch);
		
		return $result;
	}
}
