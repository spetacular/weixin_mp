<?php
$title = '使用二维码优惠券';
include 'header.php';
$mysql = SaeDB::getInstance();
$sceneid = intval($_GET['sceneid']);
$sql = "SELECT * FROM  `diner_qrcode` where sceneid = {$sceneid}";
$data = $mysql->getLine( $sql );
$mysql->closeDb();
//使用SAE Channel服务
$channelname = 'qrcheck';
$channel = new SaeChannel();
$connection = $channel->createChannel($channelname,600);
?>
<body>
<div class="qrcontainer">
    <p>您的优惠券</p>
    <p>面额：<?php echo $data['discount'];?></p>
<img class="qrimg" src="<?php echo $data['qrcode'];?>"/>
<span><a onclick="useqr(<?php echo $data['sceneid'];?>);return false;" href="useqr.php">点此使用</a></span>
<span id="tips"></span>
</div>
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<script src="http://channel.sinaapp.com/api.js"></script>
<script>
issentrsp = false;
istimeout = true;
var tips = $('#tips');
function useqr(sceneid){
    t =setTimeout("timeout()",60000);//等待1分钟
    $.post("useqr.php",{sceneid:sceneid},function(result){
        var data = JSON.parse(result);
        if(data.ret === 'ok'){            
            issentrsp = true;
            tips.html("<img src='public/images/loading_black.gif'/>"+data.msg+"……");
        }else if(data.ret === 'used' || data.ret === 'error'){
            tips.html("<img src='public/images/error.gif'/>"+data.msg+"……");
            istimeout = false;
        }
        
    });
}

var intcheck = setInterval("check()",2000);
function check(){
    if(issentrsp){
        $.post("douseqr.php",{type:2,sceneid:<?php echo $data['sceneid'];?>},function(result){            
                var data = JSON.parse(result);
                if(data.ret === 'ok'){
                    tips.html("<img src='public/images/ok.gif'/>"+data.msg+"……");
                    clearInterval(intcheck);
                    istimeout = false;
                }else if(data.ret === 'notallowed'){
                    tips.html("<img src='public/images/error.gif'/>"+data.msg+"……");
                    clearInterval(intcheck);
                    istimeout = false;
                }
        });
  }
}

function timeout(){
    if(istimeout === true){
        clearInterval(intcheck);
        tips.html("您使用优惠券的请求未得到回应，请联系前台询问……");
        
    }
}
</script>
</body>
</html>