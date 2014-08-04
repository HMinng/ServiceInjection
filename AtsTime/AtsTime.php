<?php 
namespace HMinng\AtsTime;
use HMinng\ObjectGenerator\ObjectGenerator;

/**
 * 封装了time类，防止服务器时间出现无法修正的时候方便更改
 * WARNNING: 严禁直接使用php自带的time(), date() 函数
 */
class AtsTime extends ObjectGenerator
{
	/**
	 * 返回的是当前环境的微秒浮点数
	 */
	public function micro() {
		return microtime(true);
	}

	/**
	 * 返回当前时间戳
	 * TODO：暂时由服务器同步ntp，如果出现无法预计的同步的时候，将自己实现。
	 */
	public function now() {
		return $this->locale();
	}
	
	/**
	 * 返回当前机器的时间戳
	 */
	public function locale() {
		return time();
	}
	
	/**
	 * 用date格式化时间
	 * 
	 * @param string $format 默认：Y-m-d H:i:s
	 * @param mixed $time 表示时间的值，可以是时间戳和时间字符串，默认为当前时间戳
	 * @param int $now 当第二个是时间字符串的时候，做为now的参数传入，也可以是时间字符串
	 * 
	 * @example
	 * ::format('Y-m-d');//当前时间戳
	 * ::format('Y-m-d', 1234567890);//某个时间戳
	 * ::format('Y-m-d', '+5 days');// 5天后的时间戳
	 * ::format('Y-m-d', '+5 days', 1234567890); // 从 1234567890秒开始增加5天的时间戳
	 * ::format('Y-m-d', '+5 days', '+5 days'); // total +10 days 时间戳
	 */
	public function format($format = 'Y-m-d H:i:s', $time = 0, $now = 0) {
		if (is_numeric($time)) {
			$time || $time = $this->now();
		} else {
			if (is_numeric($now)) {
				$now || $now = $this->now();
			} else {
				$now = $this->totime($now);
			}
			$time = $this->totime($time, $now);
		}
		return date($format, $time);
	}
	
	/**
	 * 将任何英文文本的日期时间描述解析为 Unix 时间戳
	 * @param $time string A date/time string
	 * $param $now The timestamp which is used as a base for the calculation of relative dates.
	 * @return int|boolean int=时间戳；false=失败
	 */
	public function totime($time, $now = 0) {
		if (is_numeric($now)) {
			$now || $now = $this->now();
		} else {
			$now = strtotime($now, $this->now());
		}
		$timestamp = strtotime($time, $now);
		if ($timestamp === false || $timestamp === -1) {
			return false;
		}
		return $timestamp;
	}
	
	/**
	 * 用来处理时间戳的起始时间，如：返回2011-09-08 00:00:00的时间戳
	 */
	public function formatToTime($format, $time = 0) {
		$time || $time = $this->now();
		return $this->totime($this->format($format, $time));
	}
	
	/**
	 * 倒计时
	 * @param int $timestamp
	 * @param int $now
	 * @param string $format
	 * @param string $numTag
	 */
	public function countdown($timestamp, $now = null, $format = 's', $numTag = '')
	{
		$now || $now = $this->now();
		$date = $timestamp - $now;
		$day = $date / 60 / 60 / 24;
		$days = (int)$day;
		$hour = $date / 60 / 60 - $days * 24;
		$hours = (int)$hour;
		$minute = $date / 60 - $days * 24 * 60 - $hours * 60;
		$minutes = (int)$minute;
		$second = $date - $days * 24 * 60 * 60 - $hours * 60 * 60 - $minutes * 60;
		$seconds = (int)$second;

		$numTagS=$numTag ? '<'.$numTag.'>' : '';
		$numTagE=$numTag ? '</'.$numTag.'>' : '';

		if(!$format){
			return array('d'=>$days, 'h'=>$hours, 'i'=>$minutes, 's'=>$seconds);
		}

		$result=$numTagS . $days . $numTagE. '天'. $numTagS . $hours .$numTagE. '小时';
		if ($format == 'h') {
			return $result;
		}
		$result.= $numTagS . $minutes . $numTagE . '分';
		if ($format == 'i') {
			return $result;
		}
		$result.= $numTagS . $seconds . $numTagE . '秒';
		return $result;
	}
}
