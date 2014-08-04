<?php 
namespace HMinng\AtsEncryption;
use HMinng\ObjectGenerator\ObjectGenerator;
use HMinng\AtsTime\AtsTime;
use HMinng\AtsCode\AtsCode;

class AtsEncryption extends ObjectGenerator 
{
	const AUTHCODE_KEY = 'U*Die)&CK^k6RD3@yNQg.G#1jcK2fx89~VO!U';
	
	public function encryption($params, $type = 'SIGNATURE', $key = '', $expiry = 0)
	{
		switch ($type) {
			case 'SIGNATURE':
				$result = self::signature($params);
				break;
			case 'ENCODE':
				$result = self::authcode(json_encode($params), 'ENCODE', $key, $expiry);
				break;
			case 'DECODE':
				$result = json_decode(self::authcode($params, 'DECODE', $key), true);
				break;
			default:
				throw new \Exception(AtsCode::getInstance()->getMessage(AtsCode::ENCRYPTION_TYPE_NUFIND), AtsCode::ENCRYPTION_TYPE_NUFIND);
		}
		
		if ( ! $result) {
			throw new \Exception(AtsCode::getInstance()->getMessage(AtsCode::ENCRYPTION_FAILURE), AtsCode::ENCRYPTION_FAILURE);
		}
		
		return $result;
	}
	
	private function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
	{
		$ckey_length = 4;
		$key = md5($key != '' ? $key : self::AUTHCODE_KEY);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(AtsTime::getInstance()->micro()), -$ckey_length)) : '';
	
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + AtsTime::getInstance()->now() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
	
		$result = '';
		$box = range(0, 255);
	
		$rndkey = array();
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if ($operation == 'DECODE') {
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - AtsTime::getInstance()->now() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
	
	private function signature($params)
	{
		if ( ! is_array($params) || empty($params)) {
			throw new \Exception(AtsCode::getInstance()->getMessage(AtsCode::ENCRYPTION_PARAMS_ARRAY), AtsCode::ENCRYPTION_PARAMS_ARRAY);
		}
		
		$string = '';
		 
		ksort($params);
	
		foreach ($params as $k => $v) {
			$string .= "$k=$v";
		}
	
		$string .= self::AUTHCODE_KEY;
		 
		return md5($string);
	}
}
