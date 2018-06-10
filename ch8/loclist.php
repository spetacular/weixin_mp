<?php
$title = '饭店列表';
include 'header.php';
$mysql = SaeDB::getInstance();
$sql = "SELECT `id`,`fname`,`loc` FROM  `diner_locs` LIMIT 10";
$data = $mysql->getData( $sql );
$mysql->closeDb();


?>
<body>
    <?php if(empty($data)){
        echo '您还未添加过饭店，<a href="addloc.php">点此添加</a>';
    }else{
    ?>
    <p><a href="addloc.php">继续添加</a></p>
    <table class="gridtable" width="98%">
        <tr>
            <th>饭店名称</th>
            <th>饭店位置</th>
            <th>操作</th>
        </tr>
        
        <?php 
        foreach($data as $item){
            echo '<tr>';
            echo "<td>{$item['fname']}</td>";
            echo "<td>{$item['loc']}</td>";
            echo "<td><a href='editloc.php?id={$item['id']}'>修改</a> <a href='delloc.php?id={$item['id']}'>删除</a></td>";
            echo '<tr>';
        }
        ?>
    </table>
    <?php }?>
</body>
</html>