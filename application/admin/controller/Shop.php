<?php
namespace app\admin\controller;

class Shop extends Base{

	/**
	 * 查看商品列表
	 * created by : xiaohai
	 * 2018/9/30
	 */
	public function index(){
		$shop_model = model('Shop');
		$where = array();
		if (input('name')) {
            $where['name'] = array('like','%'.input('name').'%');
		}
		if ((string)input('status')) {
            $where['status'] = input('status');
		}
		if (input('updatedtime')) {
			$times=explode("|",input('updatedtime'));
			$times[0]=strtotime($times[0]);
			$times[1]=strtotime($times[1]);
            $where['updated_time'] = array('between',$times);
		}
		$list = $shop_model->where($where)->order("id desc")->paginate(10,false,['query'=>request()->param()]);
        $page = $list->render();
		$this->assign('list',$list);
		$this->assign('page',$page);
        return  $this->fetch();
	}


	/**
	 * 添加商品
	 * created by : xiaohai
	 * 2018/9/30
	 */
	public function addShop(){
		if(input("post.")){
			//数据处理
			$shop_info = $_POST['m'];
            $shop_model = model('Shop');
			if(!$add_res = $shop_model->addData($shop_info)){
            	return json(['status' => 0]);
			}
            return json(['status' => 1]);
		}else{
			return $this->fetch();
		}
	}

	/**
	 * 编辑商品
	 * created by : xiaohai
	 * 2018/9/30
	 */
	public function editShop(){
		$shop_model = model('Shop');
		if(input("post.")){
			$update_id = input('post.shop_id');
			//数据处理
			$shop_info = $_POST['m'];
			if(!$update_res = $shop_model->updateData(['id' => $update_id],$shop_info)){
            	return json(['status' => 0]);
			}
            return json(['status' => 1]);
		}else{
			//获取商品信息
			$shop_id = input("param.id");
			if(!$shop_info = $shop_model->getOne(['id' => $shop_id])){
				$this->error('记录不存在');
			}
			$this->assign('info',$shop_info);
			return $this->fetch();
		}
	}



	/**
	 * 编辑商品显示状态
	 * created by : xiaohai
	 * 2018/9/30
	 */
	public function editShopStatus(){
        if(input('post.id')){
            $id = intval(input('post.id'));
            $isopen = input('post.isopen') ? input('post.isopen'):'00';
            $shop_model = model('Shop');
            $res = $shop_model->updateData(['id'=>$id] , ['status' => $isopen , 'updated_time'=>time()]);
            return json(['status' => $res]);
        }
    }
    /**
	 * 删除商品
	 * created by : xiaohai
	 * 2018/9/30
	 */
	public function delShop(){
		if(input('post.id',0,'intval')){
            $id = input('post.id',0,'intval');
            $shop_model = model('Shop');
            $res = $shop_model->where(['id'=>$id])->delete();
            return json(['status'=>$res]);
        }else{
            return json(['status'=>0]);
        }
	}

}