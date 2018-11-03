<?php
namespace app\admin\model;
use think\Model;

/**
 * 菜单操作model
 */
class AuthRule extends Model{

    protected $table = 'permission_auth_rule';


    /**
     * 显示菜单
     * @param int $type
     * @return mixed
     */
    public function selectAllMenu($type=1)
    {
        $where = array(
            'status'  => 1,
            'ismenu' => 1,
        );
        if($type == 2){
            unset($where['ismenu']);
        }
        return $this->where($where)->order('id asc')->select();
    }
    /**
     * 添加菜单
     * @param $data
     * @return mixed
     */
    public function addAdminMenu($data)
    {
        $menu_info = array(
            'name'   => isset($data['controller']) ? 'Admin/'.ucfirst($data['controller']).'/'.$data['action'] : '',
            'title'  => $data['menuname'],
            'pid'    => isset($data['pid']) ? $data['pid'] : 0,
            'ismenu' => $data['ismenu'],
        );
        
        return $this->save($menu_info);
    }

    /**
     * 查询是否已存在的opt
     * @param $controller
     * @param $action
     * @param null $id
     * @return mixed
     */
    public function isExistOpt($controller,$action=null,$id=null)
    {
        $where = array(
            'name' => 'Admin/'.$controller.'/'.$action,
            'status'    => 1,
            
        );
        if($id){
            $where['id'] = array('neq',$id);
        }
        return $this->where($where)->find();
    }

    /**
     * 是否为二级菜单
     * @param $id
     * @return bool
     */
    public function isSecondaryMenu($id)
    {
        $where = array(
            'id' => $id,
        );
        return $this->where($where)->value('pid') ? true : false;
    }

    /**
     * 编辑菜单
     * @param $data
     * @return bool
     */
    public function editAdminMenu($data)
    {
        $where = array(
            'id' => $data['id'],
        );
        $menu_info = array(
            'name'  => isset($data['controller']) ? 'Admin/'.$data['controller'].'/'.$data['action'] : '',
            'title' => $data['menuname'],
        );
        unset($data['id']);
        return $this->where($where)->update($menu_info);
    }

    /**
     * 是否存在子菜单
     * @param $id
     * @return mixed
     */
    public function isExistSonMenu($id)
    {
        $where = array(
            'pid' => $id,
            'status' => 1,
        );

        return $this->where($where)->find();
    }

    /**
     * 删除菜单
     * @param $id
     * @return bool
     */
    public function deleteAdminMenu($id)
    {
        $where = array(
            'id' => $id,
        );
        
        $data = array(
            'status' => 2,
        );
        
        return $this->where($where)->save($data);
    }

    /**
     * 根据id查询菜单信息
     * @param $id
     * @return mixed
     */
    public function selectMenuById($id)
    {
        $where = array(
            'id' => $id,
        );
        
        return $this->where($where)->find();
    }

    /**
     * 查询三级菜单
     * @param $id
     * @return mixed
     */
    public function selectOpt($id)
    {
        $where = array(
            'pid'    => $id,
            'status' => 1,
        );
        
        return $this->where($where)->select();
        
    }

    /**
     * 根据规则id数组获取菜单
     * @param $rules_arr
     * @param int $is_menu
     * @return mixed
     */
    public function getMenus($rules_arr ,$is_menu=1)
    {
        $where = array(
            'id' => array('in', $rules_arr),
            'ismenu' => 1,
            'status' => 1,   
        );

        return $this->where($where)->select();
    }

    /**
     * 查询菜单信息
     * @param $name
     * @return mixed
     */
    public function selectMenuInfoByName($name)
    {
        $where = array(
            'name' => $name,
            'status' => 1,
        );
    
        return $this->where($where)->find();
    }


}
