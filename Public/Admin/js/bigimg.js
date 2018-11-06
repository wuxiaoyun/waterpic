/**
 * Created by Administrator on 2017/6/28.
 */
        //显示图片
function over(imgid,obj,imgbig,gao) {


//大图显示的最大尺寸  4比3的大小  400 300
        maxwidth=400;
        maxheight=300;
//显示
        obj.style.display="";
        obj.style.top=gao*34+"px";
        imgbig.src=imgid.src;


        //1、宽和高都超过了，看谁超过的多，谁超的多就将谁设置为最大值，其余策略按照2、3
        //2、如果宽超过了并且高没有超，设置宽为最大值
        //3、如果宽没超过并且高超过了，设置高为最大值

        if(img.width>maxwidth&&img.height>maxheight)
        {
            pare=(img.width-maxwidth)-(img.height-maxheight);
            if(pare>=0)
                img.width=maxwidth;
            else
                img.height=maxheight;
        }
        else if(img.width>maxwidth&&img.height<=maxheight)
        {
            img.width=maxwidth;
        }
        else if(img.width<=maxwidth&&img.height>maxheight)
        {
            img.height=maxheight;
        }
}
//隐藏图片
function out(id)
{
    document.getElementById('divImage'+id).style.display="none";
}

function click_img(){
    $("#img").click();
}

