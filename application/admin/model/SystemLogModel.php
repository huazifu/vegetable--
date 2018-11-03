<?php
namespace app\admin\model;
use think\Model;
use think\paginator;

class SystemLogModel extends Model{
	protected $table = 'permission_system_log';
	/**
	 * 【系统操作日志】查询操作日志
	 */
	public function selectSystemLog($where){
		return $this->where($where)->order("id desc")->paginate(15);
	}

	/**
	 * 【系统操作日志】查询操作日志
	 */
	public function addSystemLog($editUserId,$editedUserId,$desc){
		$timeStampe = time();
		$data = array(
			'edit_user_id'   => $editUserId,
			'edited_user_id' => $editedUserId,
			'desc'           => $desc,
			'created_time'   => $timeStampe,
		);
		return $this->save($data);
	}


}