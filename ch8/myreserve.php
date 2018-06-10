<?php
$title = '我的预约';
include 'header.php';
$mysql = SaeDB::getInstance();
$openid = $mysql->escape($_GET['user']);
$sql = "SELECT * 
FROM  `diner_reserve` 
WHERE  `openid` LIKE  '{$openid}' order by dinertime desc";
$data = $mysql->getLine( $sql );//获取我的预约信息

$sql = "SELECT * FROM  `diner_locs` where id = {$data['locid']} LIMIT 1";
$loc = $mysql->getLine( $sql );//获取预约分店的信息
?>
<body>
<div class="desc_text">
    <p>预约人：<?php echo $data['name'].' ';if($data['sex']==1){echo '先生';}else{echo '女士';}?></p>
    <p>人数：<?php echo $data['num'];?></p>
    <p>用餐时间：<?php echo date("m月d日 H:i",$data['dinertime']);?></p>
    <p>电话：<?php echo $data['phone'];?></p>
    <p>分店：<?php echo $loc['fname'];?></p>
    <p>地址：<?php echo $loc['loc'];?></p>
     <p>如果您有任何意见或建议，欢迎来电</p>
    <p>兔子饭庄服务电话：<span class="num">400-400-1234</span></p>    
</div>
</body>
</html>
