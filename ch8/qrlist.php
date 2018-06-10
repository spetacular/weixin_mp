<?php
$title = '优惠券列表';
include 'header.php';
$mysql = SaeDB::getInstance();
$sql = "SELECT * from `diner_qrcode` LIMIT 10";
$data = $mysql->getData( $sql );
$mysql->closeDb();
//使用SAE Channel服务
$channelname = 'qrcheck';
$channel = new SaeChannel();
$connection = $channel->createChannel($channelname,600);
?>
<body>
    <?php if(empty($data)){
        echo '您还未添加过优惠券，<a href="addqr.php">点此添加</a>';
    }else{
    ?>
    <p><a href="addqr.php">继续添加</a></p>
    <table class="gridtable" width="98%">
        <tr>
            <th>面额</th>
            <th>校验码</th>
            <th>二维码</th>
            <th>是否使用</th>
            <th>操作</th>
        </tr>
        
        <?php 
        foreach($data as $item){
            echo '<tr>';
            echo "<td>{$item['discount']}</td>";
            echo "<td>{$item['sceneid']}</td>";
            echo "<td><img width='30px' src='{$item['qrcode']}'/></td>";
            if($item['used']){
                echo "<td>是</td>";
            }else{
                echo "<td>否</td>";
            }            
            echo "<td><a href='javascrip:' onclick=\"preview('{$item['qrcode']}');\">查看大图</a>  <a href='delqr.php?id={$item['id']}'>删除</a></td>";
            echo '<tr>';
        }
        ?>
    </table>
    <?php }?>
</body>
<script type="text/javascript">
//全局变量，用于标识WeixinJSBridge是否完成初始化，0为未完成，1为已完成
winxinJsBridgeReady = 0;
//处理WeixinJSBridgeReady事件，当初始化完成后，将winxinJsBridgeReady标记为1
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	if (typeof WeixinJSBridge == "object" && typeof WeixinJSBridge.invoke == "function") {
		winxinJsBridgeReady = 1;
	} 
});

/**
* 图片预览
*/
function preview(imgurl){
	////判断WeixinJSBridge是否完成初始化，未完成直接返回false
	if(winxinJsBridgeReady === 0){//如果不在微信中，则使用colorbox预览图片		
                $.colorbox({href:imgurl});
                return true;
	}
        //如果在微信中，调用微信的图片预览功能
	WeixinJSBridge.invoke("imagePreview",{
		"current": imgurl,
		"urls":[imgurl]
	},function(res){
		alert(res.err_msg);
	});
}


</script>
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" src="public/colorbox/jquery.colorbox-min.js"></script>
<link rel="stylesheet" type="text/css" href="public/colorbox/colorbox.css"></head>
<script src="http://channel.sinaapp.com/api.js"></script>
<script>
var channel = {
		url:"<?php echo $connection;?>",
		onMessage:function(m){
                    var data = JSON.parse(m.data);
                    if(data.type === 'requse'){
                        if(confirm("顾客将使用《"+data.sceneid+"》校验码，是否允许？")){
                                $.post("douseqr.php",{sceneid:data.sceneid,type:1},function(result){
                                        var data = JSON.parse(result);
                                        if(data.ret === 'ok'){
                                            alert("顾客已使用该校验码");
                                            location.reload();
                                        }
                                });
                            
                        }else{
                                $.post("douseqr.php",{sceneid:data.sceneid,type:0},function(result){
                                    var data = JSON.parse(result);
                                    if(data.ret === 'ok'){
                                            alert("顾客未使用该校验码");
                                        }
                                });
                        }
                    }
				
			}, 
		
		
	};
        //创建WebSocket实例
	var socket = new WebSocket(channel.url);        
        socket.onmessage = channel.onMessage;

</script>
</html>