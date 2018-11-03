<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-08-13
 * Time: 14:42
 */

namespace app\admin\controller;

class Menu extends Base{
    public function __construct(){
        parent::__construct();
    }

    //列表
    public function menulist()
    {
        $menu = $this->AuthRule->selectAllMenu();
        $menu = get_column($menu);
        foreach ($menu as $key=>$item){
            list($app,$controller,$action) = explode('/',$item['name']);
            $menu[$key]['controller'] = $controller;
            $menu[$key]['action'] = $action;
        }

        $this->assign('menu',$menu);
        return $this->fetch();
    }
    
    //添加菜单
    public function addmenu()
    {
        $pid = input('param.pid',0,'intval');
        if(input("post.")){
            $pid = input('post.pid',0,'intval');
            $rows = $_POST['m'];
            if($pid){
                //添加的菜单是否是二级菜单var
                $rows['ismenu'] = $this->AuthRule->isSecondaryMenu($pid) ? 0 : 1;
                $rows['pid'] = $pid;
            }else{
                $rows['ismenu'] = 1;
            }
            if($this->AuthRule->isExistOpt($rows['controller'], $rows['action'])){
                $logs = '添加菜单失败，原因：菜单已存在。';
                $this->addSystemLog($logs);
                return json(['status'=>0,'msg'=>"该菜单已存在"]);
            }

            $res = $this->AuthRule->addAdminMenu($rows);
            if(!$res){
                $logs = '添加菜单失败，原因：数据写入错误。';
                $this->addSystemLog($logs);
            }
            return json(['status'=>$res]);
        }else{
            $this->assign('id',$pid);
            return $this->fetch();
        }
    }

    /**
     * @description:更新菜单
     */
    public function editmenu()
    {
        if(input("post.")){
            $data = input('post.');
            if($this->AuthRule->isExistOpt($data['controller'], $data['action'],$data['id'])){
                return ['status'=>0,'msg'=>'该菜单已存在'];
            }
            $result = $this->AuthRule->editAdminMenu($data);
            if($result !== false){
                return json(['status'=>1,'msg'=>'更新成功']);
            }else{
                return json(['status'=>1,'msg'=>'更新失败']);
            }
        }else{

            $id = input("param.id",'','intval');
            $menu_info = $this->AuthRule->selectMenuById($id);
            $this->assign('menu_info',$menu_info);
            $this->assign('opt',explode('/',$menu_info['name']));
            return $this->fetch();
        }
    }

    /**
     * @description:删除菜单
     */
    public function delmenu()
    {
        $id = input('post.id',0,'intval');
        if($this->AuthRule->isExistSonMenu($id)){
            return json(['status'=>0,'msg'=>'存在子菜单未删除']);
        }
        $res = $this->AuthRule->where(['id'=>$id])->delete();
        return json(['status'=>$res]);
    }

    /**
     * @description:查看三级操作
     */
    public function viewOpt()
    {
        $id = input('get.id','','intval');

        $_opts = $this->AuthRule->selectOpt($id);

        if(!count($_opts)){
            $this->ajaxError('该菜单还未添加任何操作');
        }

        $this->assign('opts',$_opts);
        return $this->fetch();
    }
}