<?php
$title = '添加菜单';
include 'header.php';
?>
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<body>
<?php
if(isset($_GET['msg']) && $_GET['msg'] == 'emptyparams'){
    echo '<p class="error">菜名、图片地址或金额不能为空</p>';
}
?>
<form action="saveaddmenu.php" method="post" onsubmit="return check();">
    <label for="name">菜名</label>
    <input type="text" name="name" id="name"/>
    
    <label for="imgurl">图片地址</label>
    <input type="text" name="imgurl" id="imgurl"/>
    
    <label for="price">金额</label>
    <input type="text" name="price" id="price"/>
    
    <label for="category">种类</label>
    <select name="category">
        <option value="1">精美小菜</option>
        <option value="2">炒菜</option>
        <option value="3">汤羹</option>
        <option value="4">面点</option>
        <option value="5">炒饭</option>
        <option value="6">盖饭</option>
        <option value="7">饮料</option>
        <option value="8">啤酒</option>
        <option value="9">果汁</option>
    </select>
    
    <label for="available">是否上架</label>
    <span class="inlineradio">
        <input type="radio" name="available" value="0" checked/>是
        <input type="radio" name="available" value="1"/>否   
    </span>
    
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