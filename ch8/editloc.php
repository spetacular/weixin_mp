<?php
$title = '修改饭店';
include 'header.php';
$mysql = SaeDB::getInstance();
$id = intval($_GET['id']);
$sql = "SELECT * FROM  `diner_locs` where id = {$id}";
$data = $mysql->getLine( $sql );
$mysql->closeDb();
$latLng = $data['Latitude'] . ',' .$data['Longitude'];
?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
var map,markersArray = [];
function addMarker(location) {
    var marker = new qq.maps.Marker({
        position: location,
        map: map
    });
    markersArray.push(marker);
}

function clearOverlays() {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
    }
}

var init = function() {    
    map = new qq.maps.Map(document.getElementById("container"),{
        center: new qq.maps.LatLng(<?php echo $latLng;?>),
        zoom: 13
    });   
    var center = new qq.maps.LatLng(<?php echo $latLng;?>);
    addMarker(center);
    qq.maps.event.addListener(map,'click',function(event) {
        var latLng = event.latLng,
            lat = latLng.getLat().toFixed(5),
            lng = latLng.getLng().toFixed(5);
        
        clearOverlays();
	addMarker(latLng);
	var geocoder = new qq.maps.Geocoder({
            complete : function(result){
                //map.setCenter(result.detail.location);    
                document.getElementById("latLng").value = lat + ','+ lng;
                loc.setAttribute('value',result.detail.address);
//                var loc = document.getElementById("loc");
//                if(loc.value != ""){
//                    loc.value="";
//                }
//                loc.value = result.detail.address;
            }
        });
        geocoder.getAddress(latLng);
    });

}
</script>
</head>
<body onload="init()">
<?php
if(isset($_GET['msg']) && $_GET['msg'] == 'emptyparams'){
    echo '<p class="error">饭店名称和饭店位置不能为空</p>';
}
?>
<form action="saveeditloc.php" method="post" onsubmit="return check();">
    <label for="fname">饭店名称</label>
    <input type="text" name="fname" id="fname" value="<?php echo $data['fname'];?>"/>
    
    <label for="loc">饭店位置</label>
    <input type="text" name="loc" id="loc" value="<?php echo $data['loc'];?>"/>
    
    <input type="hidden" name="latLng" id="latLng" value="<?php echo $latLng;?>" />
    <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>" />
    
    <input type="submit"/>
</form>
    <div style="width:400px;height:300px" id="container"></div>
</body>
<script type="text/javascript">
    function check(){
        var fname = document.getElementById("fname").value;
        var loc = document.getElementById("loc").value;
        var latLng = document.getElementById("latLng").value;
        if(!fname){
            alert('饭店名称不能为空');
            return false;
        }
        if(!loc){
            alert('饭店位置不能为空');
            return false;
        }
        if(!latLng){
            alert('获取饭店位置经纬度错误，请重试');
            return false;
        }        
        return true;
    }
</script>
</html>

