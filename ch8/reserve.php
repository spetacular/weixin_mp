<?php
$title = '预约座位';
include 'header.php';
$mysql = SaeDB::getInstance();//获取mysql实例
$openid = $mysql->escape($_GET['user']);//获取当前用户的openid
$sql = "SELECT `id`,`fname` FROM  `diner_locs` LIMIT 10";//查询分店
$data = $mysql->getData( $sql );//获取数据
$mysql->closeDb();//关闭数据库连接
?>
<body class="bc_f9">	
	<div class="wrap gp_box">
    	<h1>请填写预约信息，我们会为您预留座位</h1>
        <div class="ap_fm_box">
            <div class="r_btn_box">
                <a class="r_arr_btn phone_ico" href="tel:400-400-1234"><span class="num">400-400-1234</span></a>
                <span class="r_arr"><a href="tel:400-400-1234"><em></em></a></span>
            </div>
            <form id="post_form" method="post" action="savereserve.php" onsubmit="return check();">  
                <input type="hidden" name="openid" value="<?php echo $openid;?>"/>
                <div class="name_box cf">
					<input placeholder="请输入姓名" id="username" name="name" type="text" />
					<input type="hidden" name="sex" id="sex" value="1" />
					<p><span class="on" id="sex_male">先生</span><span id="sex_female">女士</span></p>
				</div>
		<div class="phone_box">
                        <input type="text" placeholder="人数" id="num" name="num"/>                	
                </div>
                <div class="date_box">                        
                	<input type="text" id="dinerdate" placeholder="日期" name="dinerdate" class="date"/>
                        <input type="time" id="dinertime" placeholder="时间" name="dinertime" class="time"/>
                </div>
                <div class="phone_box">                        
                	<input type="text" placeholder="手机" id="phone" name="phone" value=""/>
                </div>
                <div class="select" style="width: 100%;height:48px;margin-top:10px;">                        
                	 <select name="locid">
                             <option value="" disabled selected>选择分店</option>
                             <?php
                             foreach ($data as $item) {
                                 echo "<option value='{$item['id']}'>{$item['fname']}</option>";
                            }
                            ?>
                            
                        </select>
                </div>
                <div style="display: none;"></div>
                <div class="ap_bot_box">
                    <p>欢迎您在兔子饭庄预约座位，为了我们对您的服务，请填写真实信息。</p>
                    <p>如果您不能按时到达本店，我们会联系您并将预约时间延长30分钟，之后将不再预留。</p>
                </div>
                <div class="ap_btn_box">
                    <a href="javascript:;" onclick="check();">预 约</a>
                </div>               
            </form>
        </div>
    </div>

</body>
<script type="text/javascript">
function check(){
    var username = document.getElementById("username").value;
    var num = document.getElementById("num").value; 
    var dinerdate = document.getElementById("dinerdate").value; 
    var dinertime = document.getElementById("dinertime").value; 
    var phone = document.getElementById("phone").value;     
    if(!username){
        alert('姓名不能为空');
        return false;
    }
    if(!num){
        alert('人数不能为空');
        return false;
    }  
    if(!dinerdate){
        alert('日期不能为空');
        return false;
    }  
    if(!dinertime){
        alert('时间不能为空');
        return false;
    }  
    if(!phone){
        alert('电话不能为空');
        return false;
    }  
    document.getElementById("post_form").submit();    
}
</script>
<script type="text/javascript" charset="utf-8" src="http://lib.sinaapp.com/js/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" src="public/datepicker/jquery.timepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="public/datepicker/jquery.timepicker.css" />
<script type="text/javascript" src="public/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="public/datepicker/bootstrap-datepicker.css" />
<script type="text/javascript">
//选择时间
$('#dinertime').timepicker({
    'showDuration': true,
    'timeFormat': 'H:i'
});
//选择日期
$('#dinerdate').datepicker({
    'format': 'mm/dd/yyyy',
    'autoclose': true
});
$(document).ready(function() {
    //切换性别
    $("#sex_male").click(function(){
            $('#sex_male').addClass('on');
            $('#sex_female').removeClass('on');
            $('#sex').val(1);
    });
    //切换性别
    $("#sex_female").click(function(){
            $('#sex_female').addClass('on');
            $('#sex_male').removeClass('on');
            $('#sex').val(2);           
    });
});
</script>
</html>