<?php
namespace app\common\model;
use think\Model;

class Shop extends Model{
	protected $insert = ['created_time','updated_time'];
	protected $update = ['updated_time'];

	//创建时间-自动完成
	protected function setCreatedTimeAttr(){
		return time();
	}
	//修改时间-自动完成
	protected function setUpdatedTimeAttr(){
		return time();
	}
	//创建时间
	protected function getCreatedTimeAttr($value){
       return $value ? date("Y-m-d H:i:s",$value) : '--';
    }
    //修改时间
    protected function getUpdatedTimeAttr($value){
       return $value ? date("Y-m-d H:i:s",$value) : '--';
    }
    public function getOne($where){
    	return $this->where($where)->find();
    }
    //获取多个用户信息
    public function getMany($where){
       $lists =$this::all($where);
       return $lists ;
    }
    public function updateData($where,$data){
    	return $this->save($data,$where);
    }
    public function addData($data){
    	return $this->save($data);
    }
}