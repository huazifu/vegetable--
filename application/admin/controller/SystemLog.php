<?php
namespace app\admin\controller;

/**
 * 【系统操作日志】
 */
class SystemLog extends Base{

	/**
	 * 查看操作日志列表
	 */
	public function index(){
		//条件查询部分
		$where = array();
		if($adminId = input("param.admin_user_id")){
			$where['edit_user_id'] = array('eq',trim($adminId));
		}
		if($edited_user = input("param.edited_user")){
			$edited_user_id = db("user")->where(['username'=>$edited_user])->value("id");
			$where['edited_user_id'] = array('eq',trim($edited_user_id));
		}
		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}
		//查询admin表中有效的用户
		$admins = $this->Admin->selectAdminInfo();
		//查询操作日志
		$log_datas = $this->SystemLogModel->selectSystemLog($where);
		$page      = $log_datas->render();

		foreach ($log_datas as $key => $value) {
			//查询管理员昵称
			$log_datas[$key]->user_name = $this->getAdminName($value->edit_user_id);

			//时间戳转时间
			$log_datas[$key]->created_time = date('Y-m-d H:i:s',$value->created_time);
			//查询被操作者信息
			$log_datas[$key]->edited_user_name = db("user")->where(['id'=>$value->edited_user_id])->value("username");

			$log_datas[$key]->edited_user_phone = db("user")->where(['id'=>$value->edited_user_id])->value("phone");
			
		}
		$this->assign("page",$page);
		$this->assign("admins",$admins);
		$this->assign("log_datas",$log_datas);
		return $this->fetch();
	}	

	/**
	 * 获取admin用户名
	 */
	protected function getAdminName($userId){
		return $this->Admin->getAdminInfoById($userId)['nikename'];
	}
	/**
     * 系统日志记录导出
     */
    public function exceldownload()
    {
        //条件查询部分
		$where = array();
		if($adminId = input("param.admin_user_id")){
			$where['edit_user_id'] = array('eq',trim($adminId));
		}

		if($edited_user = input("param.edited_user")){
			$edited_user_id = db("user")->where(['username'=>$edited_user])->value("id");
			$where['edited_user_id'] = array('eq',trim($edited_user_id));
		}

		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}

        $list = db("SystemLog")->where($where)->select();
        $title = array('管理员','用户名','描述','操作时间');

        foreach ($list as $key => $value) {
            $data[$key]['adminname'] = "\t".getadminname($value["edit_user_id"]);
            $data[$key]['edited_user_id'] = getusername($value["edited_user_id"]);
            $data[$key]['desc'] = $value["desc"];
            $data[$key]['created_time'] = date("Y-m-d H:i:s",$value["created_time"]);
        }
        exportCsv($data,$title);
    }

    /**
     * 【普通用户】金豆变动记录
     */

    public function balanceLog(){
    	$balanceLog = db("BalanceLog");
    	$where = array();
    	if($change_type = input("param.change_type")){
			$where['change_type'] = array('eq',trim($change_type));
		}
		if($user_phone = input("param.user_phone")){
			$uid = db("user")->where(['phone'=>$user_phone])->value("id");
			$where['userid'] = array('eq',trim($uid));
		}
		if($from_userphone = input("param.from_userphone")){
			$uid = db("agent")->where(['phone'=>$from_userphone])->value("id");
			$where['from_userid'] = array('eq',trim($uid));
		}

		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}
		$list = $balanceLog->where($where)->order("id desc")->paginate(15);
		$page = $list->render();
		$this->assign("list",$list);
		$this->assign("page",$page);
		return $this->fetch();
    }

    /**
     * 【普通用户】到处资金变动
     */
    public function exceldownloadBalanceLog(){
    	$balanceLog = db("BalanceLog");
    	$where = array();
    	if($change_type = input("param.change_type")){
			$where['change_type'] = array('eq',trim($change_type));
		}
		if($user_phone = input("param.user_phone")){
			$uid = db("user")->where(['phone'=>$user_phone])->value("id");
			$where['userid'] = array('eq',trim($uid));
		}
		if($from_userphone = input("param.from_userphone")){
			$uid = db("agent")->where(['phone'=>$from_userphone])->value("id");
			$where['from_userid'] = array('eq',trim($uid));
		}
		
		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}

        $list = $balanceLog->where($where)->select();
        $title = array('用户名','手机号码','变动类型','变动前金豆','变动金豆','变动后金豆','变动时间','备注');

        foreach ($list as $key => $value) {
			$data[$key]['username']  = "\t".getusername($value["userid"]);
			$data[$key]['userphone'] = getuserphone($value["userid"]);
            switch ($value["change_type"]) {
                case 1:
                    $data[$key]['change_type'] = "系统增加";
                    break;
                case 2:
                    $data[$key]['change_type'] = "系统减少";
                    break;
                case 3:
                    $data[$key]['change_type'] = "贈送";
                    break;
                case 4:
                    $data[$key]['change_type'] = "消费";
                    break;
                case 5:
                    $data[$key]['change_type'] = "被贈送";
                    break;
                default:
                    $data[$key]['change_type'] = "未知";
            }
			$data[$key]['before_balance'] = $value["before_balance"];
			$data[$key]['change_balance'] = $value["change_balance"];
			$data[$key]['after_balance']  = $value["after_balance"];
			$data[$key]['created_time']   = date("Y-m-d H:i:s",$value["created_time"]);
			$data[$key]['contentstr']     = $value["contentstr"];
        }
        exportCsv($data,$title);
    }


    /**
     * 金豆变动记录
     */

    public function agentbalanceLog(){
    	$agentbalanceLog = db("AgentBalanceLog");
    	$where = array();
    	if($change_type = input("param.change_type")){
			$where['change_type'] = array('eq',trim($change_type));
		}
		if($agent_phone = input("param.agent_phone")){
			$uid = db("agent")->where(['phone'=>$agent_phone])->value("id");
			$where['agentid'] = array('eq',trim($uid));
		}
		if($user_phone = input("param.user_phone")){
			$uid = db("user")->where(['phone'=>$user_phone])->value("id");
			$where['to_userid'] = array('eq',trim($uid));
		}
		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}
		$list = $agentbalanceLog->where($where)->order("id desc")->paginate(15);
		$page = $list->render();
		$this->assign("list",$list);
		$this->assign("page",$page);
		return $this->fetch();
    }

    /**
     * 【会员】到处资金变动
     */
    public function exceldownloadAgentBalanceLog(){
    	$agentbalanceLog = db("AgentBalanceLog");
    	$where = array();
    	if($change_type = input("param.change_type")){
			$where['change_type'] = array('eq',trim($change_type));
		}
		if($agent_phone = input("param.agent_phone")){
			$uid = db("agent")->where(['phone'=>$agent_phone])->value("id");
			$where['agentid'] = array('eq',trim($uid));
		}
		if($user_phone = input("param.user_phone")){
			$uid = db("user")->where(['phone'=>$user_phone])->value("id");
			$where['to_userid'] = array('eq',trim($uid));
		}
		if($createdTime = input("param.createtime")){
			$tims = explode(" | ", $createdTime);
			$where['created_time'] = array('between',[trim(strtotime($tims[0])),trim(strtotime($tims[1]))]);
		}

        $list = $agentbalanceLog->where($where)->select();
        $title = array('用户名','手机号码','变动类型','变动前金豆','变动金豆','变动后金豆','变动时间','备注');

        foreach ($list as $key => $value) {
			$data[$key]['username']  = "\t".getagentname($value["agentid"]);
			$data[$key]['userphone'] = getagentphone($value["agentid"]);
            switch ($value["change_type"]) {
                case 1:
                    $data[$key]['change_type'] = "系统增加";
                    break;
                case 2:
                    $data[$key]['change_type'] = "系统减少";
                    break;
                case 3:
                    $data[$key]['change_type'] = "赠送";
                    break;
                case 4:
                    $data[$key]['change_type'] = "消费";
                    break;
                case 5:
                    $data[$key]['change_type'] = "被赠送";
                    break;
                default:
                    $data[$key]['change_type'] = "未知";
            }
			$data[$key]['before_balance'] = $value["before_balance"];
			$data[$key]['change_balance'] = $value["change_balance"];
			$data[$key]['after_balance']  = $value["after_balance"];
			$data[$key]['created_time']   = date("Y-m-d H:i:s",$value["created_time"]);
			$data[$key]['contentstr']     = $value["contentstr"];
        }
        exportCsv($data,$title);
    }



}