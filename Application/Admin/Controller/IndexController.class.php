<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {


    public function index(){

        $this->display();

    }
    //用户列表
    public function userlist(){
        $ModelAdmin=M('Auth_user');
        $List=$ModelAdmin->select();
        $auth_group_access=M('Auth_group_access');
        $auth_group=M('Auth_group');
        foreach($List as $key=>$val){
            $group_id=$auth_group_access->where("uid='$val[id]'")->getField('group_id');
            $List[$key]['group_name']=$auth_group->where("id='$group_id'")->getField('title');
        }
        $this->assign('List',$List);
        $this->display();
    }
    //删除用户
    public function del(){
        $ModelAdmin=M('Auth_user');
        $id=I('get.id');
        $s=$ModelAdmin->where("id='$id'")->delete();
        if($s){
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }
    //编辑用户
    public function edit(){
        $ModelAdmin=M('Auth_user');
        $xiugaiid=I('post.admin_id');
        if(!empty($xiugaiid)){
            $adminpwd2=I('post.adminpwd2');
            $adminpwd3=I('post.adminpwd3');
            if(empty($adminpwd2)){
                $where['group_id']=I('post.zhiweiid');
                $auth_group_access=M('Auth_group_access');
                $data['email']=I('post.email');
                $s=$ModelAdmin->where("id='$xiugaiid'")->save($data);

                $auth_group_access->where("uid=$xiugaiid")->save($where);
                $this->success('修改成功',U('Index/userlist'));
            }else{
                if($adminpwd2==$adminpwd3 && !empty($adminpwd2)){
                    $data['password']=md5($adminpwd2);
                    $data['email']=I('post.email');
                    $s=$ModelAdmin->where("id='$xiugaiid'")->save($data);
                    if($s){
                        $where['group_id']=I('post.zhiweiid');
                        $auth_group_access=M('Auth_group_access');
                        $auth_group_access->where("uid=$xiugaiid")->save($where);
                        $this->success('修改成功',U('Index/userlist'));
                    }else{
                        $this->error("修改失败请重试");
                    }
                }else{
                    $this->error("两次密码输入不一致");
                }
            }

        }else{
            $auth_group_access=M('Auth_group_access');
            $auth_group=M('Auth_group');
            $list=$auth_group->select();
            $this->assign('List',$list);
            $id=I('get.id');
            $getone=$ModelAdmin->where("id=$id")->find();
            $group_id=$auth_group_access->where("uid=$id")->getField('group_id');
            $this->assign('group_id',$group_id);
            $this->assign('getone',$getone);
            $this->display();
        }
    }
    //添加用户
    public function add(){
        if(IS_POST){
            $ModelAdmin=M('Auth_user');
            $adminpwd2=I('post.adminpwd2');
            $adminpwd3=I('post.adminpwd3');
            if($adminpwd2==$adminpwd3) {
                $data['password'] = md5($adminpwd2);
                $data['username']=I('post.username');
                $data['email']=I('post.email');
                $s=$ModelAdmin->add($data);
                if($s){
                    $where['group_id']=I('post.zhiweiid');
                    $where['uid']=$s;
                    $auth_group_access=M('Auth_group_access');
                    $auth_group_access->add($where);
                    $this->success('添加用户成功',U('Index/userlist'));
                }else{
                    $this->error("添加用户失败，请重试");
                }
            }else{
                $this->error("两次密码输入不一致");
            }

        }else{
            $auth_group=M('Auth_group');
            $list=$auth_group->select();
            $this->assign('List',$list);
            $this->display();
        }
    }
    //修改密码
    public function myeditpwd(){
        $ModelAdmin=M('Auth_user');
        if(IS_POST){
            $uid=session('uid');
            $adminpwd1=I('post.adminpwd1');
            $adminpwd2=I('post.adminpwd2');
            $adminpwd3=I('post.adminpwd3');
            $mima=$ModelAdmin->where("id=$uid")->find();
            if(md5($adminpwd1)!=$mima['password']){
                $this->error("老密码输入错误");
            }
            if($adminpwd2==$adminpwd3) {
                $data['password'] = md5($adminpwd2);
                $s=$ModelAdmin->where("id='$uid'")->save($data);
                if($s){
                    $this->success('修改密码成功',U('Index/userlist'));
                }else{
                    $this->error("修改密码失败，请重试");
                }
            }else{
                $this->error("两次密码输入不一致");
            }
        }else{
            $uid=session('uid');
            $getone=$ModelAdmin->where("id=$uid")->find();
            $this->assign('getone',$getone);
            $this->display();
        }
    }
}