<?php 
namespace HMinng\AtsResponse;
use HMinng\ObjectGenerator\ObjectGenerator;
use HMinng\AtsCode\AtsCode;
use HMinng\AtsLog\AtsLog;

class AtsResponse extends ObjectGenerator { 
	public function success($traceId, $fromId, $toId, $data, $params = array(), $url = null) { 
		$respone = array( 'code' => AtsCode::SUCCESS, 'data' => $data); 
		$this->show($traceId, $fromId, $toId, $respone, $params, $url);
	}
	
	public function failure($traceId, $fromId, $toId, $code, $params = array(), $url = null)
	{
		$respone = array(
			'code' => $code,
			'message' => AtsCode::getInstance()->getMessage($code)
		);
		
		$this->show($traceId, $fromId, $toId, $respone, $params, $url);
	}
	
	private function show($traceId, $fromId, $toId, $respone, $params, $url)
	{
		AtsLog::getInstance()->write($traceId, $fromId, $toId, $respone, $params, $url);
		
		throw new \Exception($respone['message'], $respone['code']);
	}
}