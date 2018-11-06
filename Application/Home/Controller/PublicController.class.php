<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/23
 * Time: 10:44
 */

namespace Home\Controller;
use Think\Controller;
class PublicController extends Controller{

    public function _initialize(){
        
        if (ismobile()) {
            //设置默认默认主题为 Mobile
            C('DEFAULT_V_LAYER','Mobile');
        }
    }
}
