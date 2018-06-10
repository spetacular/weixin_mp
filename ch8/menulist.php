<?php
$title = '菜单列表';
include 'header.php';
$mysql = SaeDB::getInstance();
$sql = "SELECT * FROM  `diner_menu` LIMIT 10";
$data = $mysql->getData( $sql );
$mysql->closeDb();
$categoryconfig = array(
        "1" =>'精美小菜',
        "2"=>'炒菜',
        "3"=>'汤羹',
        "4"=>'面点',
        "5"=>'炒饭',
        "6"=>'盖饭',
        "7"=>'饮料',
        "8"=>'啤酒',
        "9"=>'果汁',  
);
?>
<body>
    <?php if(empty($data)){
        echo '您还未添加过菜单，<a href="addmenu.php">点此添加</a>';
    }else{
    ?>
    <p><a href="addmenu.php">继续添加</a></p>
    <table class="gridtable" width="98%">
        <tr>
            <th>菜名</th>
            <th>图片</th>
            <th>价格</th>
            <th>种类</th>
            <th>是否上架</th>
            <th>操作</th>
        </tr>
        
        <?php 
        foreach($data as $item){
            echo '<tr>';
            echo "<td>{$item['name']}</td>";
            echo "<td><img width='30px' src='{$item['imgurl']}'/></td>";
            echo "<td>{$item['price']}</td>";
            echo "<td>{$categoryconfig[$item['category']]}</td>";
            if($item['available'] == 0){
                echo "<td>是</td>";
            }else{
                echo "<td>否</td>";
            }  
            echo "<td><a href='editmenu.php?id={$item['id']}'>修改</a> <a href='delmenu.php?id={$item['id']}'>删除</a></td>";
            echo '<tr>';
        }
        ?>
    </table>
    <?php }?>
</body>
</html>