<?php
$title = '点菜';
include 'header.php';
$category = intval($_GET['cat']);
$mysql = SaeDB::getInstance();
if($category){//如果带有有效的cat参数，则获取该种类的菜肴清单
    $sql = "SELECT * FROM  `diner_menu` where category = {$category} and available = 0 LIMIT 10";
}else{//如果没有有效的cat参数，则默认返回全部种类菜肴
    $sql = "SELECT * FROM  `diner_menu` where available = 0 LIMIT 10";
}
$data = $mysql->getData( $sql );
$mysql->closeDb();
?>
<script>
//当页面向下滑动时保持顶部导航条固定不变
window.onscroll = function() {
    var wint = document.documentElement.scrollTop;
    if (wint === 0) wint = document.body.scrollTop;
    var omng = document.getElementById("menu_nav");
    var head = document.getElementById("header");
    if (omng) {
        if (omng.offsetTop < wint - 5) omng.style.position = 'fixed';
        else omng.style.position = 'static';
    }
}
//切换显示
function toggle(o, id, m, l) {
    c = document.getElementById(id);
    if (c.style.display == 'none') {
        c.style.display = '';
    } else {
        c.style.display = 'none';
    }
    return false;
}
</script>
<body class="bc_f9">
    <div class="topbar">
        <div class="menu_nav">
            <a href="#">首页</a><a href="#">客户端</a>
        <a class="more" href="javascript:"><img src="public/images/more_menu.jpg" width="43" height="32" border="0" onclick="toggle(this, 'popnav', '', '')"></a>
        </div>
    <div id="popnav" class="popnav" style="display: none;">
        <div class="menu_cat">
            <ul>
                <li class="pops"><a href="#">菜品</a></li>
                <li><a href="menu.php?cat=1">精美小菜</a></li>
                <li><a href="menu.php?cat=2">炒菜</a></li>
                <li><a href="menu.php?cat=3">汤羹</a></li>

                <li class="pops"><a href="#">主食</a></li>
                <li><a href="menu.php?cat=4">面点</a></li>
                <li><a href="menu.php?cat=5">炒饭</a></li>
                <li><a href="menu.php?cat=6">盖饭</a></li>

                <li class="pops"><a style="position:relative;" href="#">酒水</a></li>
                <li><a href="menu.php?cat=7">饮料</a></li>
                <li><a href="menu.php?cat=8">啤酒</a></li>
                <li><a href="menu.php?cat=9">果汁</a></li>
            </ul> 
        </div>
    </div>
</div>
  
<div class="menulist">
    <ul class="noborder">
        <?php 
        if(empty($data)){
            echo '没有此类美食，点些其它美食吧^_^';
        }
        foreach ($data as $item){?>
        <li><div class="menu_item">
            <a href="#"><img src="<?php echo $item['imgurl'];?>" width="150" height="150" border="0" alt="<?php echo $item['name'];?>" />

                <span class="menu_item_desc"><?php echo $item['name'];?></span>
                <span class="menu_item_desc"><?php echo $item['price'];?>元</span>
            </a></div>
        </li>   
        <?php }?> 
    </ul>
</body>
</html>