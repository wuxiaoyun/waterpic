<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <title>照片加日期水印</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="/Public/Home/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Home/css/style.css" rel="stylesheet">
    <link href="/Public/Home/js/bootstrap-fileinput/fileinput.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="/Public/Home/js/html5shiv.min.js"></script>
    <script src="/Public/Home/js/respond.min.js"></script>
    <![endif]-->
    
    <style>
	.glyphicon.glyphicon-plus-sign{
		display:none;
	}
	</style>

</head>
<body>
	 <form method="post" action="<?php echo U('Test/upload');?>" id="passForm" enctype="multipart/form-data" multipart="">
		  <p class="form-group"><h3 style="margin:10px">请上传照片</h3></p>
		  <p>
		  	<span style="color: red;margin:10px">注：每张照片大小不可超过5M，且最多可以传十张，照片为手机拍摄照片，图片名格式以IMG_开头，如IMG_20180715_185030.jpg</span><br />
		  	
		  </p>
		  
		  <input name="images[]" type="file" id="uploadFile" multiple class="file-loading" accept="image/*" />
		  <div id="error_msg" style="color:red;margin:10px"></div>
		  <div id="result_msg" style="color:green;margin:10px;font-size:18px"></div>
		  
		  <div class="date" style="text-align:center; margin-top:50px">
		    	<input type="text" id="test1" placeholder="照片日期" name="date"  />
				<i class="icon_1 icon_time"></i>
			</div>
			<div class="progress">
			 	<div class="progress-bar progress-bar-striped"><span class="percent">0%</span></div>
			</div>
			
			<div class="confirm" style="text-align:center">
				<input type="hidden" id="confirm_tag"  value="0" />
				<input type="button" id="confirm_pic"  value="添加水印" />
				<a href="" id="download">下载水印图片</a>
			</div>
			
			<div id='outerdiv' style='position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:2;width:100%;height:100%;display:none;'>
			    <div id='innerdiv' style='position:absolute;'>
			        <img id='bigimg' src='' />
			    </div>
			</div>
     </form>
    
<script src="/Public/Home/js/jquery.min.js"></script>
<script src="/Public/Home/js/bootstrap.min.js"></script>
<script src="/Public/Home/laydate/layer.js"></script>
<script src="/Public/Home/laydate/laydate.js"></script>
<script src="/Public/Home/js/jquery.form.js"></script>
<script src="/Public/Home/js/bootstrap-fileinput/fileinput.js"></script>
<script src="/Public/Home/js/bootstrap-fileinput/zh.js"></script>
<!-- Initialize Swiper -->
<script type="text/javascript">
//日期
laydate.render({
	elem: '#test1',
});

	$(function() {
		 //初始化fileinput
		 var fileInput = new FileInput();
		 fileInput.Init("uploadFile", "<?php echo U('Test/upload');?>");
	});

	//初始化fileinput
	var FileInput = function() {
	      var oFile = new Object();

	      //初始化fileinput控件（第一次初始化）
	      oFile.Init = function(ctrlName, uploadUrl) {
		      var control = $('#' + ctrlName);
		      //初始化上传控件的样式
			  control.fileinput({
				   language: 'zh', //设置语言
				   uploadUrl: "<?php echo U('Test/upload');?>", //上传的地址
				   allowedFileExtensions: ['jpg', 'png', 'gif'], //接收的文件后缀
				   uploadAsync: false, //默认同步上传
				   //showBrowse: false,  //是否显示浏览按钮
				   //showPreview: false, //是否显示预览区域
				   showUpload: false, //是否显示上传按钮
				   showRemove: true, //显示移除按钮
				   showCaption: true, //是否显示标题
				   dropZoneEnabled: true, //是否显示拖拽区域
				   //deleteUrl: "<?php echo U('Test/delete');?>", //删除图片时的请求路径
				   //minImageWidth: 50, //图片的最小宽度
				   //minImageHeight: 50,//图片的最小高度
				   //maxImageWidth: 1000,//图片的最大宽度
				   //maxImageHeight: 1000,//图片的最大高度
				   maxFileSize:5*1024,//单位为kb，如果为0表示不限制文件大小
				   //minFileCount: 0,
				   maxFileCount: 10, //表示允许同时上传的最大文件个数
				   enctype: 'multipart/form-data',
				   browseClass: "btn btn-primary", //按钮样式: btn-default、btn-primary、btn-danger、btn-info、btn-warning  
				   previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
				   layoutTemplates : {
		                /* actionDelete:‘‘, */ //去除上传预览的缩略图中的删除图标
		                actionUpload : '', //去除上传预览缩略图中的上传图片；
		                /* actionZoom:‘‘ */   //去除上传预览缩略图中的查看详情预览的缩略图标。
		            },
			  });
		      
		 }
	      
		 return oFile; //这里必须返回oFile对象，否则FileInput组件初始化不成功
	};
	
	
		$(document).ready(function(){
			
		    //预览
			function view(a){
				a.each(function(){
					$(this).click(function(){
						//var _this = $(this);//将当前的pimg元素作为_this传入函数  
						var _this = $(this).parents(".kv-preview-thumb").find("img");
				        imgShow("#outerdiv", "#innerdiv", "#bigimg", _this);  
					})
				})
			}
		    
		    
			function imgShow(outerdiv, innerdiv, bigimg, _this){  
		        var src = _this.attr("src");//获取当前点击的pimg元素中的src属性  
		        $(bigimg).attr("src", src);//设置#bigimg元素的src属性  
		      
		            /*获取当前点击图片的真实大小，并显示弹出层及大图*/  
		        $("<img/>").attr("src", src).load(function(){  
		            var windowW = $(window).width();//获取当前窗口宽度  
		            var windowH = $(window).height();//获取当前窗口高度  
		            var realWidth = this.width;//获取图片真实宽度  
		            var realHeight = this.height;//获取图片真实高度  
		            var imgWidth, imgHeight;  
		            var scale = 0.8;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放  
		              
		            if(realHeight>windowH*scale) {
		            	//判断图片高度  
		                imgHeight = windowH*scale;//如大于窗口高度，图片高度进行缩放  
		                imgWidth = imgHeight/realHeight*realWidth;//等比例缩放宽度  
		                if(imgWidth>windowW*scale) {
		                	//如宽度扔大于窗口宽度  
		                    imgWidth = windowW*scale;//再对宽度进行缩放  
		                }  
		            } else if(realWidth>windowW*scale) {
		            	//如图片高度合适，判断图片宽度  
		                imgWidth = windowW*scale;//如大于窗口宽度，图片宽度进行缩放  
		                imgHeight = imgWidth/realWidth*realHeight;//等比例缩放高度  
		            } else {
		            	//如果图片真实高度和宽度都符合要求，高宽不变  
		                imgWidth = realWidth;  
		                imgHeight = realHeight;  
		            }  
		            $(bigimg).css("width",imgWidth);//以最终的宽度对图片缩放  
		              
		            var w = (windowW-imgWidth)/2;//计算图片与窗口左边距  
		            var h = (windowH-imgHeight)/2;//计算图片与窗口上边距  
		            $(innerdiv).css({"top":h, "left":w});//设置#innerdiv的top和left属性  
		            $(outerdiv).fadeIn("fast");//淡入显示#outerdiv及.pimg  
		        });  
		          
		        $(outerdiv).click(function(){
		        	//再次点击淡出消失弹出层  
		            $(this).fadeOut("fast");  
		        });  
		    }  
			
			$("#confirm_pic").click(function(){
				var confirm_tag = $("#confirm_tag").val();
				if (confirm_tag==1) {
					alert("已经添加水印，请勿重复操作！ 如有需要请刷新页面重新操作！");
					return false;
				}
				var progress = $(".progress"); 
				var progress_bar = $(".progress-bar");
				var percent = $('.percent');
				var formData = new FormData($("#passForm")[0]);
				$("#passForm").ajaxSubmit({ 
			  		dataType:  'json', //数据格式为json 
			  		beforeSend: function() { //开始上传 
			  			progress.show();
			  			var percentVal = '0%';
			  			progress_bar.width(percentVal);
			  			percent.html(percentVal);
			  		}, 
			  		uploadProgress: function(event, position, total, percentComplete) { 
			  			var percentVal = percentComplete + '%'; //获得进度 
			  			progress_bar.width(percentVal); //上传进度条宽度变宽 
			  			percent.html(percentVal); //显示上传进度百分比 
			  		}, 
			  		success: function(msg) {
			  			var error_msg = '';
			  			for(var i = 0; i < msg.length; i++){
			  				if(msg[i]['status'] == 'error') {
			  					//添加水印出错信息
			  					error_msg += '<p>'+msg[i]['msg']+'</p>';
			  				}
						};
						$("#error_msg").html(error_msg)
						
						var path = new Array();
						imglist = $(".kv-preview-thumb");
						var result_count = 0;
						imglist.each(function(i){
							if(msg[i]['status'] != 'error') {
								//水印图片替换掉之前的图片
								$(this).find("img").attr("src", msg[i]['msg']);
								path.push(msg[i]['msg']);
								result_count++;
							}
						})
						
						
						//隐藏原来的预览按钮，展示新的预览按钮
						$(".kv-file-zoom").css('display','none'); 
						var viewlist = $(".file-footer-buttons");
						var viewbutton = "<button type='button' class='pimg btn btn-sm btn-kv btn-default btn-outline-secondary'><i class='glyphicon glyphicon-zoom-in'></i></div>"
						viewlist.each(function(){
							$(this).append(viewbutton);
						})
						
						$("#result_msg").html("成功添加水印图片 "+result_count+" 张")
						$("#confirm_tag").val("1");
						$("#uploadFile").attr('disabled',true);
		            	view($(".pimg"));
		            	
		            	//下载水印图片
		  			    var link="<?php echo U('Test/picDownload');?>?path="+path;
		  			    $("#download").attr("href",link);
		  			    $("#download").show();
		  			    
		  			    setTimeout(function(){progress.hide()}, 2000);
		  			  	
			  		}, 
			  		error:function(){
			  			alert("异常！");
				        progress.hide();
				        return false;
			  		} 
			  	});
			
		});
	
});
	
	

</script>
</body>
</html>