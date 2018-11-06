<?php
namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller {

    public function main(){
        $this->display();

    }

    public function header(){
        $this->display();
    }

    public function menu(){
        $this->display();
    }

    public function menugroup(){
        $this->display();
    }

    public function yzm(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->useImgBg = true;
        $Verify->entry();
    }
    /*
	 * 用户登录
	 */
    public function login(){

       $this->display();
    }
    public function checklogin(){
        $adminname=I('post.adminname');
        $adminpwd=I('post.adminpwd');

        $yzm=I('post.yzm');

        if($this->check_verify($yzm)){
            if(empty($adminname) ||empty($adminpwd)){
                $this->error('用户名或密码错误!');
            }
            $ModelAdmin=M('Auth_user');
            $adminpwd=md5($adminpwd);
            $getoneAdmin=$ModelAdmin->where("username='$adminname' and password='$adminpwd'")->find();
           /* echo $ModelAdmin->getLastSql();
            die();*/
            if(empty($getoneAdmin)){
                $this->error('用户名或密码错误!');
            }else{
                session('name',$adminname);
                session('uid',$getoneAdmin['id']);
                echo "<script language=\"javascript\">window.open('".__ROOT__."/index.php/Admin"."','_top');</script>";
            }
        }else{
            $this->error('验证码输入错误!');
        }

    }
    //验证码
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
    //退出
    public function unlogin(){
        session(null);
        echo "<script language=\"javascript\">window.open('".__ROOT__."/index.php/Admin"."','_top');</script>";
    }

}