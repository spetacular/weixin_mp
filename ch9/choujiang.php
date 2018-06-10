<?php 
require 'lib/common.func.php';
require 'lib/weixin.class.php';
$openid = 0;
if($_GET['code']){   
    $ret = weixin::getAuthToken($_GET['code']);//网页授权获取用户的openid
    if(isset($ret['openid'])){
        $openid = $ret['openid'];
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=screen-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<title>水果抽奖_微商城</title>
<link rel="stylesheet" type="text/css" href="public/shop.css"/>
</head>
<body>
<div class="container">
      <div class="frutmachine">
        <div class="mask mask1"></div>
        <div class="mask mask2"></div>
        <div class="mask mask3"></div>
        <a class="share" href="javascript:void(0);">开始抽奖吧，少年</a>
      </div>
</div>
<div>
    <h3>抽奖规则</h3>
    <p>1.点击开始，水果即开始转动</p>
    <p>2.待水果静止，出现相同的水果时表示您中奖,获得奖励。</p>
    <p>3.出现不同的水果时表示您未中奖,请再接再厉。</p>
</div>    
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/jquery.backgroundPosition.js"></script>
<script type="text/javascript" charset="utf-8" src="public/jquery.easing.js"></script>
<script type="text/javascript">
//判断当前浏览器是否为微信浏览器
function checkMicroMessenger(){
    var pattern = /MicroMessenger/ig;
    if(pattern.test(navigator.userAgent)){
        return true;
    }else{
        if (typeof WeixinJSBridge == "object"){
            return true;
        }else{
            return false;
        }
    }
}
//产生1到n之间的随机数
function getRandom(n){
    return Math.floor(Math.random()*n+1);
}
//0草莓---1桔子---2葡萄---3西瓜---4西红柿---5香蕉
$(function(){
    var isBegin = false;//是否开始抽奖
    var itemHeight = 50;//抽奖图片中单个奖品高度
    var itemPadding = 10;//抽奖图片中两个相邻奖品的高度间隔
    var picHeight = 360;//抽奖图片高度
     var randomPadding = getRandom(50);//停靠位置随机化
    $('.container .share').click(function(){
            if(isBegin) return false;
            if(!checkMicroMessenger()){
                alert('请在微信里抽奖！');
                return false;
            }

            isBegin = true;
            $(".frutmachine .mask").css('backgroundPosition','6px 4px');
            $.post("lottery.php",{'id':'<?php echo $openid;?>' }, function(result,status){
              var data = JSON.parse(result);
              if(data['result'] == -2){
                  alert(data['msg']);
                  return false;
              }
              $(".frutmachine .mask").each(function(index){
                    var _num = $(this);
                    setTimeout(function(){
                            _num.animate({ 
                                    backgroundPosition: '6px '+(picHeight*5+((itemHeight+itemPadding)*(7-data['info'][index])+randomPadding))+'px'
                            },{
                                    duration: 6000+index*3000,
                                    easing: "easeInOutCirc",
                                    complete:function(){
                                            if(index===2){
                                                    isBegin = false;
                                                    if(data['result'] == 0){
                                                        alert('运气差了点，再接再厉吧');
                                                    }else{
                                                        alert('恭喜你抽中'+data['msg']);
                                                    } 

                                            }
                                    }
                            });
                    }, index * 300);
            });
            });

    });
});
</script>

</body>
</html>
