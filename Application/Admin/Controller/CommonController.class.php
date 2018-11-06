<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/1
 * Time: 17:20
 */
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        header("Content-type:text/html;charset=utf-8");
        $loginuid=session('uid');
        if(empty($loginuid)){
            $this->redirect('Public/login',"",1, '请登录，1称后自动跳转到登录页面');
        }
        /*if(!action_AuthCheck()) {
            $this->error("没有操作权限");
        }*/
    }
}