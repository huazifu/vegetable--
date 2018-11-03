<?php
namespace app\admin\controller;

class Program extends Base{

	public function index(){
		$program_model       = model('Program');
		$program_info        = $program_model->getProgram(['id'=>1]);
		$this->assign("vo",$program_info);
		return $this->fetch();
	}


	public function saveProgram(){
		$updata['title']        = input("title");
		$updata['appid']        = input("appid");
		$updata['secret']       = input("secret");
		$updata['logo']       = input("old_logo");
		$updata['background_img']       = input("old_background_img");
		$updata['updated_time'] = time();
		//上传文件
		$maxSize  = 8388608 ;
		$exts     = array('jpg', 'png', 'gif');
		$savePath = ROOT_PATH . 'public' . DS . 'uploads';
		//信息验证
		if($image = request()->file('logo')){
			$image_info = $image->validate(['size'=>$maxSize,'ext'=>$exts])->move($savePath);
			if(!$image_info){
				$this->error($image->getError());
			}
			$updata['logo'] = "\/".$image_info->getSaveName();
		}
		if($image = request()->file('background_img')){
			$image_info = $image->validate(['size'=>$maxSize,'ext'=>$exts])->move($savePath);
			if(!$image_info){
				$this->error($image->getError());
			}
			$updata['background_img'] = "\/".$image_info->getSaveName();
		}
		$program_model    = model('Program');
		if($program_res = $program_model->updateProgram(['id'=>'1'] , $updata)){
			return json(['status'=>1,'msg'=>'修改成功']);
		}
		return json(['status'=>0,'msg'=>'修改失败']);
	}


}