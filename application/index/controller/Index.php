<?php
namespace app\index\controller;
use think\Loader;
use think\Db;
class Index extends Base{
	/**
	 * 获取系统信息
	 */
	public function index(){
		$system_info =model("program")->getProgram(1);
		$shops=model("shop")->getMany(['status'=>'01']);
		$this->assign('shops',$shops);
		$this->assign('system_info',$system_info);
		return view();
	    
	}
	
}
