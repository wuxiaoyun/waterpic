<?php
return array(
    'TMPL_EXCEPTION_FILE'    =>  './404/404.html',
    //'配置项'=>'配置值'
    'URL_HTML_SUFFIX'=>'html',//伪静态
    //'配置项'=>'配置值'
    'MODULE_ALLOW_LIST' => array('Home','Admin'),
    'DEFAULT_MODULE'       =>    'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
    //    路由规则
    'URL_ROUTER_ON' => TRUE,
    'URL_ROUTE_RULES' => array(


        '/^admin/'  => 'Index/index', //禁止后台admin登陆

        '/^ezweb/'  => '/Admin/index',
        
        '/^product-details-(\d+)/'=>'Product/product_info?id=:1',
        '/^product/'=>'Product/index', //产品与服务
        
        '/^scene-(\d+)/'=>'Scene/index?id=:1',
        '/^scene/'=>'Scene/index', //场景与应用
        
        '/^bazaar/'=>'Bazaar/index', //市场活动
        
        '/^client-(\d+)-(\d+)/'=>'Client/index?id=:1&erji=:2',
        '/^client-(\d+)/'=>'Client/index?id=:1',
        '/^client/'=>'Client/index', //我们的客户
        
        '/^about-case-details-(\d+)/'=>'About/about_case_details?id=:1',
        '/^about-details-(\d+)/'=>'About/about_details?id=:1',
        '/^about-news-a/'=>'About/news?flag=1', //全部新闻
        '/^about-news/'=>'About/news', //新闻
        '/^about-honor/'=>'About/honor', //荣誉
        '/^about-case-a/'=>'About/cases?flag=1', //全部案例分享
        '/^about-case/'=>'About/cases', //案例分享
        '/^about/'=>'About/index', //关于道生,道生简介，大事记
        
		'/^contact-add/'=>'Contact/addmessage', //留言成功
        '/^contact/'=>'Contact/index', //联系我们
    ),

    'AUTH_CONFIG' => array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'think_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'think_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'think_auth_rule', //权限规则表
        'AUTH_USER' => 'think_auth_user' //用户信息表
    ),
    'WebConfig_VersionName'=>'易点',
    //'配置项'=>'配置值'
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => 'localhost', // 服务器地址
    'DB_NAME'   => 'daosh', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'think_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
);