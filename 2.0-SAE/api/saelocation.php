<?php
header('Content-type:text/html;charset=utf-8');

$SaeLocationObj = new SaeLocation();

/*
//根据起点与终点数据查询自驾车路线信息
$drive_route_arr = array('begin_coordinate'=>'116.317245,39.981437','end_coordinate'=>'116.328422,40.077796');
$drive_route = $SaeLocationObj->getDriveRoute($drive_route_arr);
echo 'drive_rote: ';
print_r($drive_route);
echo '</br>';
*/

/*
// 失败时输出错误码和错误信息
if ($bus_route === false)
    var_dump($SaeLocationObj->errno(), $SaeLocationObj->errmsg());
*/


/*

//根据起点与终点数据查询公交乘坐路线信息
$bus_route_arr = array('begin_coordinate'=>'116.317245,39.981437','end_coordinate'=>'116.328422,40.077796');
$bus_route = $SaeLocationObj->getBusRoute($bus_route_arr);
echo 'bus_rote: ';
print_r($bus_route);
echo '</br>';


// 根据关键词查询公交线路及其站点信息
$bus_line_arr = array('q'=>'320路区间');
$bus_line = $SaeLocationObj->getBusLine($bus_line_arr);
echo 'bus_line: ';
print_r($bus_line);
echo '</br>';


//根据关键词查询公交线路
$bus_station_arr = array('q'=>'回龙观');
$bus_station = $SaeLocationObj->getBusStation($bus_station_arr);
echo 'bus_station: ';
print_r($bus_station);
echo '</br>';
*/

//根据IP地址返回地理信息坐标
$ip_to_geo_arr = array('ip'=>'202.106.0.20,202.108.5.20');
$ip_to_geo = $SaeLocationObj->getIpToGeo($ip_to_geo_arr);
echo 'ip_to_geo: ';
print_r($ip_to_geo);
echo '</br>';

 ?>
