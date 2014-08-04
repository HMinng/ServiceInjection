<?php
namespace HMinng\AtsConfig;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsConfig extends ObjectGenerator
{
	private $urls = array(
		'BasicData' 			=> 'http://api.basicdata.baicheng.com/', 			// 基础数据
		'SupplierManagement' 	=> 'http://api.supplier.baicheng.com/',				//供应商系统
		'VisaFare' 				=> 'http://192.168.3.154:11002/',					//政策
		'VisaOrder' 			=> 'http://192.168.3.154:11003/',					//订单
		'PayCenter' 			=> 'http://192.168.3.154:11015/',					//支付
		'VisaEngine' 			=> 'http://192.168.3.154:11007/',					//引擎
		'CustomerService' 		=> 'http://192.168.3.154:11014/',					//客服
		'ConfigManageSystem' 	=> 'http://192.168.3.154:11016/',					//cms
		'TransferSystem' 		=> 'http://192.168.3.154:11018/',					//物流
		'AtsPush'   			=> 'http://192.168.3.213:80/',						//推送
		'Passport' 				=> 'http://apippt.baicheng.com/',					//会员
		'WenDa' 				=> 'http://wenda.baicheng.com/',					//问答
		'PayCenterManager' 		=> 'http://192.168.3.154:11025/',					//支付中心管理后台
		'TeMai'					=> 'http://temai.internal.baicheng.com/'			//特卖
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
