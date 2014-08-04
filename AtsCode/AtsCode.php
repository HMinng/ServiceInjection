<?php 
namespace HMinng\AtsCode;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsCode extends ObjectGenerator
{
	const UNKNOWN   = -1;
	const UNDEFINED = 0;
	
	const SUCCESS   = 100000;
	
	const OPERATION_FAILURE 		= 10040002;
	const SYSTEM_ID_NOT_FOUND 		= 10040003;
	const CREATE_CURL_FAILURE 		= 10040004;
	const INVOKE_CLASS_UNDEFIND 	= 10040005;
	const INVOKE_URL_UNDEFIND 		= 10040006;
	const ENCRYPTION_TYPE_NUFIND 	= 10040007;
	const ENCRYPTION_FAILURE 		= 10040008;
	const ENCRYPTION_PARAMS_ARRAY 	= 10040009;
	const PARAMS_ERROR				= 10040010;
	
	public function getMessage($id)
	{
		$message = array(
			-1 => '未知错误',
			0 => '未定义',
			
			100000 => '操作成功',
				
			10040002 => '操作失败',
			10040003 => '系统id不存在，请联系heming@byecity.com',
			10040004 => 'Curl句柄创建失败',
			10040005 => '调用类未定义',
			10040006 => '调用URL未定义',
			10040007 => '加密类型未定义',
			10040008 => '加解密失败',
			10040009 => '加签参数必须为数组',
			10040010 => '参数错误'
		);
		
		if ( ! array_key_exists($id, $message)) {
			return $message[self::OPERATION_FAILURE];
		}
		
		return $message[$id];
	}
}
