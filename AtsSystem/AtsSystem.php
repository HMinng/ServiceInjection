<?php 
namespace HMinng\AtsSystem;
use HMinng\ObjectGenerator\ObjectGenerator;

class AtsSystem extends ObjectGenerator
{
    //权限控制系统
    const Competence = 1001;
    
    //签证政策系统
    const VisaFare = 1002;
    
    //订单系统
    const VisaOrder = 1003;
    
    //公共开发库
    const Lib = 1004;
    
    //透明代理
    const Proxy = 1005;
    
    //用户系统
    const Passport = 1006;
    
    //预定引擎
    const VisaEngine = 1007;
    
    //通用错误代码
    const Error = 1008;
    
    //Push系统
    const Push = 1009;
    
    //供应商系统（web页面）
    const Supplier = 1010;
    
    //内部运营系统（web页面）
    const Operations = 1011;

    //基础数据服务系统
    const BasicData =1012;
    
    //消息推送系统
    const AtsPush = 1013;
    
    //客服系统
    const CustomerService = 1014;
    
    //支付中心
    const PayCenter = 1015;
    
    //配置管理系统
    const ConfigManageSystem = 1016;

    //供应商管理系统（接口）:分公司管理
    const SupplierManagement = 1017;

    //物流系统
    const TransferSystem = 1018;
	
	//保险系统
	const Insurance = 1019;
	
	//签证web端
	const VisaWeb = 1020;
	
	//名字服务
	const NameService = 1021;
	
	//特卖
	const TeMai = 1022;
	
	//问答
	const WenDa = 1023;
	
	//App版本管理
	const MobileVersion = 1024;
	
	//支付中心管理系统
	const PayCenterManager = 1025;
    
     private $classNames = array(
         1001 => 'Competence',          //权限控制系统
         1002 => 'VisaFare',            //签证政策系统
         1003 => 'VisaOrder',           //订单系统
         1004 => 'Lib',                 //公共开发库
         1005 => 'Proxy',               //透明代理
         1006 => 'Passport',            //用户系统
         1007 => 'VisaEngine',          // 用户端引擎系统
         1008 => 'Error',               //通用错误代码
         1009 => 'Push',                //Push系统
         1010 => 'Supplier',            //供应商系统（web页面）
         1011 => 'Operations',          //内部运营系统（web页面）
         1012 => 'BasicData',           // 基础数据服务系统
         1013 => 'AtsPush',				//消息推送系统
     	 1014 => 'CustomerService',		//客服系统
         1015 => 'PayCenter',			//支付中心
         1016 => 'ConfigManageSystem',	//配置管理系统
         1017 => 'SupplierManagement',  //供应商管理系统（接口）:分公司管理
         1018 => 'TransferSystem',      //物流系统
		 1019 => 'Insurance',			//保险系统
		 1020 => 'VisaWeb',				//签证web端
		 1021 => 'NameService',			//名字服务
		 1022 => 'TeMai',				//特卖
		 1023 => 'WenDa',				//问答
     	 1024 => 'MobileVersion',		//App版本管理
     	 1025 => 'PayCenterManager',	//支付中心管理系统
     );
    
    public function getClassNameById($id)
    {
        if ( ! array_key_exists($id, $this->classNames)) {
            return false;
        }
        
        return $this->classNames[$id];
    }
    
    public function getClassNames()
    {
        return $this->classNames;
    }
}
