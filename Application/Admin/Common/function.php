<?php
function action_AuthCheck($userId,$relation='and'){
//$relation = or|and; //默认为'or' 表示满足任一条规则即通过验证; 'and'则表示需满足所有规则才能通过验证
    $ruleName = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME; //规则唯一标识,取当前的控制器:Admin/Index/index
    $Auth = new \Think\Auth();

    if(empty($userId)){ //用户ID判断，没有就取当前登录的用户ID
        $userId = $_SESSION['uid'];
    }
    //$userId=1;
    $type=1; //分类-具体是什么没搞懂，默认为:1
    $mode='url'; //执行check的模式,默认为:url
    return $Auth->check($ruleName,$userId,$type,$mode,$relation);
}

//发送邮件
function sendemail($title,$content,$email){
    vendor('PHPMailer.class#phpmailer');
    date_default_timezone_set("Asia/Shanghai");//设置时区
    set_time_limit(120);//最长执行120秒
    $mail = new PHPMailer();//实例化
    $mail->IsSMTP();//使用SMTP协议发送
    $mail->SMTPAuth = true;
    //$mail->Host = "";//SMTP服务器
    $mail->Port = 25;//SMTP服务器端口
    //$mail->Username = '';//发送邮件的用户名
    //$mail->Password = '';//密码
    $mail->SetFrom('', '');
    $mail->AddReplyTo('','');
    $mail->CharSet = "UTF-8";//字符集
    $mail->Encoding = "base64";//编码
    $mail->IsHTML(true);//以HTML发送
    $mail->Subject = $title;
    $mail->AltBody ="text/html";

    if(!empty($content) && !empty($email)){
        //echo $content; die();
        $mail->MsgHTML($content);
        $mail->AddAddress($email);     //设置收件的地址
        $mail->Send();
        $mail->ClearAddresses();
    }

}