<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function unlimitedForLayer($cate, $name = 'child', $pid = 0)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['pid'] == $pid) {
            $v[$name] = unlimitedForLayer($cate, $name, $v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function getAllMenu($model){
    //从数据库读取菜单
    $cate = $model->where('status = 1')->order('id asc')->select();     //读取用作菜单显示的
    //var_dump($cate);die;
    $menu=unlimitedForLayer($cate);
    //var_dump($model->_sql());
    //var_dump($menu);die;
    return $menu;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * description: 递归菜单
 * @param unknown $array
 * @param number $fid
 * @param number $level
 * @param number $type 1:顺序菜单 2树状菜单
 * @return multitype:number
 */
function get_column($array,$type=1,$fid=0,$level=0)
{
    $column = [];
    if($type == 2)
        foreach($array as $key => $vo){
            if($vo['pid'] == $fid){
                $vo['level'] = $level;
                $column[$key] = $vo;
                $column [$key][$vo['id']] = get_column($array,$type=2,$vo['id'],$level+1);
            }
        }else{
        foreach($array as $key => $vo){
            if($vo['pid'] == $fid){
                $vo['level'] = $level;
                $column[] = $vo;
                $column = array_merge($column, get_column($array,$type=1,$vo['id'],$level+1));
            }
        }
    }

    return $column;
}

/**
 * 通过用户ID获取用户姓名
 */
function getusername($id){
    if ($id == 0) {
        return "-";
    }
    $User = db("user");
    $username = $User->where("id=" . $id)->value("username");
    return $username;
}
/**
 * 通过用户ID获取用户手机号码
 */
function getuserphone($id){
    if ($id == 0) {
        return "-";
    }
    $User = db("user");
    $userphone = $User->where("id=" . $id)->value("phone");
    return $userphone;
}

function getuseravatar($id){
    if ($id == 0) {
        return "-";
    }
    $User = db("user");
    $useravatar = $User->where("id=" . $id)->value("avatar");
    return $useravatar;
}

/**
 * 通过管理员ID获取管理员姓名
 */
function getadminname($id){
    if ($id == 0) {
        return "-";
    }
    $User = db("admin");
    $username = $User->where("id=" . $id)->value("nikename");
    return $username;
}

function random_str($length = 32)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ ){
        $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}
//导出CSV
function exportCsv($list,$title){
    $file_name="CSV".date("mdHis",time()).".csv";
    header ( 'Content-Type: application/vnd.ms-excel' );
    header ( 'Content-Disposition: attachment;filename='.$file_name );
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    $file = fopen('php://output',"a");
    $limit=10000;
    $calc=0;
    //列名
    foreach ($title as $v){
        $tit[]=iconv('UTF-8', 'GB2312//IGNORE',$v);
    }
    //将数据通过fputcsv写到文件句柄
    fputcsv($file,$tit);

    foreach ($list as $v){
        $calc++;
        if($limit==$calc){
            ob_flush();
            flush();
            $calc=0;
        }
        foreach ($v as $t){
            $tarr[]=iconv('UTF-8', 'GB2312//IGNORE',$t);
        }
        fputcsv($file,$tarr);
        unset($tarr);
    }
    unset($list);
    fclose($file);
    exit();
}

/**
 * 获取高校分类名称
 */
function getSchoolClass($school_class_id){
    if ($school_class_id == 0) {
        return "--";
    }
    $SchoolClass = db("school_class");
    $class_title = $SchoolClass->where("id=" . $school_class_id)->value("class_title");
    return $class_title;
}