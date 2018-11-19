<?php
namespace Home\Controller;
class IndexController extends PublicController  {

    public function index(){
        
        $this->display();
    }
    
    
    //添加水印
    public function pic() {
        
        $pics = I('post.pic');
        $paths = I('post.path');
		$date = I("post.date");
        
        //获取图片时间
        if (!empty($pics)) {
            foreach ($pics as $key=>$val) {
                if (strpos($val, 'IMG_') === 0) {
                    //符合手机拍照的格式, 获取时间
    				if($date!='') {
    					$time = $date;
    				} else {
    					$time = substr($val, 4, 8);
    					$year = substr($time, 0, 4);
    					$month = substr($time, 4, 2);
    					$day = substr($time, 6, 2);
    					$time = $year.'-'.$month.'-'.$day;
    				}
                    
                    $public_path = $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/';
                    $dst_path = $public_path.$paths[$key];
                    
                    //创建图片的实例
                    $dst = imagecreatefromstring(file_get_contents($dst_path));
                    //打上文字
                    $font = $_SERVER['DOCUMENT_ROOT'].'/Public/Home/fonts/youyuan.ttf';//字体路径
                    
                    $color = imagecolorallocate($dst,255,255,255);//字体颜色,白色
                    //获取图片的信息（得到图片的基本信息）
                    $info = getimagesize($dst_path);
                    $dst_w = $info[0];
                    $dst_h = $info[1];
                    $dst_type = $info[2];
                    //获取图片类型
                    $type = image_type_to_extension($dst_type,false);
                    //文字大小
                    $font_size = 80;
                    imagefttext($dst, $font_size, 0, $dst_w-600, $dst_h-100, $color, $font, $time);
                    //浏览器输出
                    //header("Content-type:{$info['mime']}");
                    $func = "image{$type}";
                    //$func($dst);
                    
                    //保存图片，覆盖原图片
                    /*$oldpic = $public_path.$paths[$key];
                    $func($dst,$oldpic);*/
                    $newimage = substr($paths[$key], 0, strripos($paths[$key],'.')).'-new';
                    $type = substr($paths[$key], strripos($paths[$key],'.'));
                    $newimage = $public_path.$newimage.$type;
                    $func($dst,$newimage);
                    
                    $response = array(
                        'status' => 'success',
                        'message' => '添加水印成功！',
                    );
                    
                } else {
                    $response = array(
                        'status' => 'fail',
                        'message' => '照片格式错误，必须以IMG_开头，如IMG_20180715_185030.jpg'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'fail',
                'message' => '请上传照片！'
            );
        }
        
        $this->ajaxReturn($response);
    }
    
    //上传图片
    public function upload() {
        
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     5242880 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/Uploads/'; // 设置附件上传根目录
        $date = I("post.date");
        foreach($_FILES['images']['name'] as $k=>$v) {
            if ($v) {
                if($date!='' || strpos($v, 'IMG_') === 0) {
                    //输入日期或者文件格式IMG_开头
                    if ($date!='') {
                        $time = $date;
                    } else {
                        $time = substr($v, 4, 8);
                        $year = substr($time, 0, 4);
                        $month = substr($time, 4, 2);
                        $day = substr($time, 6, 2);
                        $time = $year.'-'.$month.'-'.$day;
                    }
                    
                    $pic_arr = array(
                        'name' => $v,
                        'type' => $_FILES['images']['type'][$k],
                        'tmp_name' => $_FILES['images']['tmp_name'][$k],
                        'error' => $_FILES['images']['error'][$k],
                        'size' => $_FILES['images']['size'][$k],
                    );
                    //上传
                    $info   =   $upload->uploadOne($pic_arr);
                    if(!$info) {
                        // 上传错误提示错误信息
                        if($upload->getError()!='没有文件被上传！'){
                            $data[] = array(
                                'status' => 'error',
                                'msg' => "{$v} 上传失败！"
                            );
                        }
                    
                    } else {
                        // 上传成功 获取上传文件信息
                        $image_path = $info['savepath'].$info['savename'];
                    
                        //上传成功后添加水印
                        $public_path = $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/';
                        $dst_path = $public_path.$image_path;
                    
                        //创建图片的实例
                        $dst = imagecreatefromstring(file_get_contents($dst_path));
                        //打上文字
                        $font = $_SERVER['DOCUMENT_ROOT'].'/Public/Home/fonts/youyuan.ttf';//字体路径
                    
                        $color = imagecolorallocate($dst,255,255,255);//字体颜色,白色
                        //获取图片的信息（得到图片的基本信息）
                        $info = getimagesize($dst_path);
                        $dst_w = $info[0];
                        $dst_h = $info[1];
                        $dst_type = $info[2];
                        //获取图片类型
                        $type = image_type_to_extension($dst_type,false);
                        //文字大小
                        $font_size = 70;
                        imagefttext($dst, $font_size, 0, $dst_w-600, $dst_h-100, $color, $font, $time);
                        //浏览器输出
                        $func = "image{$type}";
                    
                        //保存图片，覆盖原图片
                        $func($dst,$dst_path);
                    
                        $data[] = array(
                            'status' => 'success',
                            'msg' => '/Public/Uploads/'.$image_path
                        );
                    
                    }
                    
                } else {
                    //文件格式不是IMG_开头
                    $data[] = array(
                        'status' => 'error',
                        'msg' => "{$v} 添加水印失败，失败原因： 照片格式错误，必须以IMG_开头，如IMG_20180715_185030.jpg"
                    );
                }
                
            } else {
                $data[] = array(
                    'status' => 'error',
                    'msg' => "请上传照片！"
                );
            }
        }
        
        echo json_encode($data);
    }
    
    //删除图片
    public function delete() {
        $public_path = $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/';
        $file_path = I('post.file_path');
        $file = $public_path.$file_path;
        
        if(file_exists($file)) {
            unlink($file);
        }
    }
    
    //批量下载图片
    public function picDownload(){
        header("Content-type: text/html; charset=utf-8");
        
        $path = explode(',', I('get.path'));

        $public_path = $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/';
        $filename = $public_path.date ( 'YmdH' ) . ".zip";
        
        // 生成文件
        $zip = new \ZipArchive ();
        // 使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        if ($zip->open ($filename ,\ZipArchive::OVERWRITE) !== true) {
            //OVERWRITE 参数会覆写压缩包的文件 文件必须已经存在
            if($zip->open ($filename ,\ZipArchive::CREATE) !== true){
                // 文件不存在则生成一个新的文件 用CREATE打开文件会追加内容至zip
                echo '无法打开文件，或者文件创建失败';
                exit;
            }
        }

        foreach($path as $v){
            $swfimglist =  $_SERVER['DOCUMENT_ROOT'].$v;
            if(file_exists($swfimglist)){
                $zip->addFile($swfimglist, basename($swfimglist));
            } else {
                continue;
            }
        }
        // 关闭
        $zip->close ();
        //清空（擦除）缓冲区并关闭输出缓冲
        ob_end_clean();
        //下面是输出下载;
        header ( "Cache-Control: max-age=0" );
        header ( "Content-Description: File Transfer" );
        header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
        header ( "Content-Type: application/zip" ); // zip格式的
        header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
        header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
        @readfile ( $filename );//输出文件;
        flush();
        
        //删除包和文件
        unlink($filename);
        
        foreach($path as $val){
            $file = $_SERVER['DOCUMENT_ROOT'].$v;
        
            if(file_exists($file)) {
                unlink($file);
            }
        }
        exit;
    }
    
    
}
