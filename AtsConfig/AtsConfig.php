<?php
namespace HMinng\AtsConfig;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsConfig extends ObjectGenerator
{
	private $urls = array(
		// 基础数据
		'BasicData' => 'http://api.basicdata.baicheng.com/',
		// 供应商系统
		'SupplierManagement' => 'http://api.supplier.baicheng.com/',
		// 政策
		'VisaFare' => 'http://192.168.3.154:11002/',
		// 订单
		'VisaOrder' => 'http://192.168.3.154:11003/',
		// 支付
		'PayCenter' => 'http://192.168.3.154:11015/',
		// 引擎
		'VisaEngine' => 'http://192.168.3.154:11007/',
		// 客服
		'CustomerService' => 'http://192.168.3.154:11014/',
		// cms
		'ConfigManageSystem' => 'http://192.168.3.154:11016/',
		// 物流
		'TransferSystem' => 'http://192.168.3.154:11018/',
		// 推送
		'AtsPush'   => 'http://192.168.3.207:80/',
		// 会员
		'Passport' => 'http://apippt.baicheng.com/',
		// 问答
		'WenDa' => 'http://wenda.baicheng.com/',
	);
	
	public function getNode($node)
	{
		$this->isExist($node);
		
		return $this->$node;
	}
	
	private function isExist($node)
	{
		$class = new \ReflectionClass($this);
		$properties = $class->getDefaultProperties();
		
		if ( ! is_array($properties) || empty($properties)) {
			throw new \Exception('未配置任何配置！');
		}
		
		if ( ! array_key_exists($node, $properties)) {
			throw new \Exception($node . '未配置！');;
		}
		
		return true;
	}
}