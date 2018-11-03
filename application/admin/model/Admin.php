<?php
namespace app\admin\model;
use think\Model;
/**
 * 菜单操作model
 */
class Admin extends Model{
    protected $table = 'permission_admin';

    /**
     * 【通过用户ID查询admin用户】
     */
    public function getAdminInfoById($userId){
    	$where = array(
    		'id' => $userId,
    	);

    	return $this->where($where)->find();
    }

    /**
     * 查询所有的管理员
     */
    public function selectAdminInfo(){

    	return $this->order("id asc")->field('id,nikename')->select();
    }

}
