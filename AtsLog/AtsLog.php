<?php 
namespace HMinng\AtsLog;
use HMinng\AtsCode\AtsCode;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsLog extends ObjectGenerator
{	
	public $startTime;
	
	private $time;
	
	private $execTime;
	
	public function write($traceId, $fromId, $toId, $message, $params = array(), $url = null)
	{
		$message = $this->processMessage($message);
		
		$params = $this->processParams($params);
		
		$execTime = $this->processTime($params);
		
		$logInfo = $this->packing($traceId, $fromId, $toId, $message, $params, $url);
		
		$this->send($logInfo);
	}
	
	private function processMessage($message)
	{
		if (empty($message)) {
			$message = array(
				'code' => AtsCode::UNKNOWN,
				'message' => AtsCode::getMessage(AtsCode::UNKNOWN)
			);
		}

		return json_encode($message);
	}
	
	private function processParams($params)
	{
		return ! empty($params) ? json_encode($params) : 'nothing';
	}
	
	private function processTime()
	{
		$this->time = date('Y-m-d H:i:s');
		
		$endTime = microtime(true);
		
		$this->execTime = round($endTime - $this->startTime, 4);
		
		return true;
		
	}
	
	private function packing($traceId, $fromId, $toId, $message, $params, $url)
	{
		$fromIp = array_key_exists('SERVER_ADDR', $_SERVER) ? $_SERVER['SERVER_ADDR'] : array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : '';
		
		$logInfo = '【' . $this->time . '】	exec_time	【' . $this->execTime . '】	trace_id:	【' . $traceId . '】	from_ip	【' . $fromIp . '】	from_id	【' . $fromId . ' 】	to_id	【' . $toId . '】	url:【' . $url . '】	params:【' . $params . '】	respone:【' . $message . '】';
		
		return $logInfo;
	}
	
	private function send($message) 
	{
  		openlog('System Invoke', LOG_PID, LOG_LOCAL0);
	
  		syslog(LOG_INFO, "Messagge: $message");
		
  		closelog();

//  		error_log($message."\r\n", 3, 'd:/1.log');
	}
}


