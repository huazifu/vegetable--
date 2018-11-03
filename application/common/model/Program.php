<?php
namespace app\common\model;
use think\Model;
class Program extends Model 
{
    protected $updata = ['updated_time'];
  
	//更新时间字段自动完成	
    protected function setUpdatedTimeAttr()
    {
        return time();
    }
	//更新时间字段转换
    public function getUpdatedTimeAttr($value)
    {
       return date("Y-m-d H:i:s",$value);
    }

    public function getBackgroundImgAttr($value)
    {
      $Newvalue=''; 
      if(!empty($value)){
         $Newvalue=str_replace('\\','/',$value);
         $Newvalue=config('domain').'/uploads'.$Newvalue;
       }
      
      
       return [$value,$Newvalue];
    }
    public function getLogoAttr($value)
    {
      $Newvalue=''; 
      if(!empty($value)){
         $Newvalue=str_replace('\\','/',$value);
         $Newvalue=config('domain').'/uploads'.$Newvalue;
       }
      
       return [$value,$Newvalue];
    }
    
  
	//获取
   public function getProgram($where){
	   $result =$this::get($where);
	   return $result ;
   }
   
   //更新用户信息
   public function updateProgram($where,$fields){
	   $result=$this::where($where)->update($fields);
	   return $result;
    
   }
   
}
?>