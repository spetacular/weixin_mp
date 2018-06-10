<?php
$title = '编辑菜单';
include 'header.php';
$mysql = SaeDB::getInstance();
$id = intval($_GET['id']);
$sql = "SELECT * FROM  `diner_menu` where id = {$id}";
$data = $mysql->getLine( $sql );
$mysql->closeDb();
?>
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<body>
<?php
if(isset($_GET['msg']) && $_GET['msg'] == 'emptyparams'){
    echo '<p class="error">菜名、图片地址或金额不能为空</p>';
}
?>
<form action="saveeditmenu.php" method="post" onsubmit="return check();">
    <label for="name">菜名</label>
    <input type="text" name="name" id="name" value="<?php echo $data['name'];?>"/>
    
    <label for="imgurl">图片地址</label>
    <input type="text" name="imgurl" id="imgurl"  value="<?php echo $data['imgurl'];?>"/>
    
    <label for="price">金额</label>
    <input type="text" name="price" id="price"  value="<?php echo $data['price'];?>"/>
    
    <label for="category">种类</label>
    <select name="category">
        <option value="1" <?php if($data['category'] == 1)echo 'selected';?>>精美小菜</option>
        <option value="2" <?php if($data['category'] == 2)echo 'selected';?>>炒菜</option>
        <option value="3" <?php if($data['category'] == 3)echo 'selected';?>>汤羹</option>
        <option value="4" <?php if($data['category'] == 4)echo 'selected';?>>面点</option>
        <option value="5" <?php if($data['category'] == 5)echo 'selected';?>>炒饭</option>
        <option value="6" <?php if($data['category'] == 6)echo 'selected';?>>盖饭</option>
        <option value="7" <?php if($data['category'] == 7)echo 'selected';?>>饮料</option>
        <option value="8" <?php if($data['category'] == 8)echo 'selected';?>>啤酒</option>
        <option value="9" <?php if($data['category'] == 9)echo 'selected';?>>果汁</option>
    </select>
    
    <label for="available">是否上架</label>
    <span class="inlineradio">
        <input type="radio" name="available" value="0" <?php if($data['available'] == 0)echo 'checked';?>/>是
        <input type="radio" name="available" value="1" <?php if($data['available'] == 1)echo 'checked';?>/>否   
    </span>
    <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>" />
    <input type="submit"/>
</form>

</body>
<script type="text/javascript">
function check(){
    var name = document.getElementById("name").value;
    var imgurl = document.getElementById("imgurl").value; 
    var price = document.getElementById("price").value; 
    if(!name){
        alert('菜名不能为空');
        return false;
    }
    if(!imgurl){
        alert('图片地址不能为空');
        return false;
    }   
    
    if(!price){
        alert('金额不能为空');
        return false;
    }    
    return true;
}
</script>
</html>