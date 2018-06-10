<?php
$title = '添加饭店';
include 'header.php';
?>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script>
var map,markersArray = [];
//添加标识
function addMarker(location) {
    var marker = new qq.maps.Marker({
        position: location,
        map: map
    });
    markersArray.push(marker);
}
//清除标识
function clearOverlays() {
    if (markersArray) {
        for (i in markersArray) {
            markersArray[i].setMap(null);
        }
    }
}
//初始
var init = function() {
    //以container为地图容器，在网页中创建一个地图
    map = new qq.maps.Map(document.getElementById("container"),{
        center: new qq.maps.LatLng(39.916527,116.397128),
        zoom: 13
    });
    //根据客户端IP定位地图中心位置
    citylocation = new qq.maps.CityService({
        complete : function(result){
            map.setCenter(result.detail.latLng);
        }
    });
    citylocation.searchLocalCity();    
    //为地图上的点击事件添加监听
    qq.maps.event.addListener(map,'click',function(event) {
        var latLng = event.latLng,
            lat = latLng.getLat().toFixed(5),
            lng = latLng.getLng().toFixed(5);
        
        clearOverlays();//清除所有标识
	addMarker(latLng);//在点击位置处添加标识
	var geocoder = new qq.maps.Geocoder({
            complete : function(result){  //当完成反地址解析后，将经纬度写回latLng表单字段，将地址写回loc表单字段
                document.getElementById("latLng").value = lat + ','+ lng;
                document.getElementById("loc").value = result.detail.address;
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
<form action="saveaddloc.php" method="post" onsubmit="return check();">
    <label for="fname">饭店名称</label>
    <input type="text" name="fname" id="fname"/>
    
    <label for="loc">饭店位置</label>
    <input type="text" id="loc" name="loc"/>
    
    <input type="hidden" id="latLng" name="latLng"/>
    
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