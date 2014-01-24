<meta charset="utf-8">
<?php
function weatherAPI($Location_X,$Location_Y){
	$map_api_url='http://api.map.baidu.com/geocoder?coord_type=wgs84&location='.$Location_X.','.$Location_Y;
	$output=file_get_contents($map_api_url);
	$mapxml=simplexml_load_string($output);
	$province=str_replace('省', '', $mapxml->result->addressComponent->province);
	$city=str_replace(array('市','县','区'), array('','',''), $mapxml->result->addressComponent->city);

	if(file_exists('CityID.xml')){
		$wxml = simplexml_load_file('CityID.xml');
		for ($i=0; $i < 34; $i++) { 
			if($province==$wxml->Province[$i][Name]){
				for ($j=0; $j < count($wxml->Province[$i]->City); $j++) { 
					if ($city==$wxml->Province[$i]->City[$j][Name]) {
						$cityID=$wxml->Province[$i]->City[$j][ID];
					}
				}
			}
		}
	}
	else{}

	$weather_api_url='http://m.weather.com.cn/data/'.$cityID.'.html';
	$weather=file_get_contents($weather_api_url);
	$wea=json_decode($weather,1);

	$weaData=$wea['weatherinfo']['city']."\n";
	$weaData.='发布日期:'.$wea['weatherinfo']['date_y'].' '.$wea['weatherinfo']['week']."\n";
	$weaData.='今日气温:'.$wea['weatherinfo']['temp1'].'/'.$wea['weatherinfo']['tempF1']."\n";
	$weaData.='天气:'.$wea['weatherinfo']['weather1']."\n";
	$weaData.='状态:'.$wea['weatherinfo']['index']."\n";
	$weaData.='建议:'.$wea['weatherinfo']['index_d']."\n";
	$weaData.='风向风力:'.$wea['weatherinfo']['wind1']."\n";
	$weaData.='紫外线:'.$wea['weatherinfo']['index_uv']."\n";
	$weaData.='洗车指数:'.$wea['weatherinfo']['index_xc']."\n";
	$weaData.='旅游指数:'.$wea['weatherinfo']['index_tr']."\n";
	$weaData.='舒适指数:'.$wea['weatherinfo']['index_co']."\n";
	$weaData.='晨练指数:'.$wea['weatherinfo']['index_cl']."\n";
	$weaData.='晾晒指数:'.$wea['weatherinfo']['index_ls']."\n";
	$weaData.='感冒指数:'.$wea['weatherinfo']['index_ag']."\n";
	$weaData.="\n".'未来几天天气'."\n";
	$weaData.='明日:'.$wea['weatherinfo']['temp2'].'/'.$wea['weatherinfo']['tempF2']."\n";
	$weaData.=$wea['weatherinfo']['weather2']."\n";
	$weaData.=$wea['weatherinfo']['wind2']."\n";
	$weaData.='第三日:'.$wea['weatherinfo']['temp3'].'/'.$wea['weatherinfo']['tempF3']."\n";
	$weaData.=$wea['weatherinfo']['weather3']."\n";
	$weaData.=$wea['weatherinfo']['wind3']."\n";
	$weaData.='第四日:'.$wea['weatherinfo']['temp4'].'/'.$wea['weatherinfo']['tempF4']."\n";
	$weaData.=$wea['weatherinfo']['weather4']."\n";
	$weaData.=$wea['weatherinfo']['wind4']."\n";
	$weaData.='第五日:'.$wea['weatherinfo']['temp5'].'/'.$wea['weatherinfo']['tempF5']."\n";
	$weaData.=$wea['weatherinfo']['weather5']."\n";
	$weaData.=$wea['weatherinfo']['wind5']."\n";
	$weaData.='第六日:'.$wea['weatherinfo']['temp6'].'/'.$wea['weatherinfo']['tempF6']."\n";
	$weaData.=$wea['weatherinfo']['weather6']."\n";
	$weaData.=$wea['weatherinfo']['wind6']."\n";

	return $weaData;
}
// for test
/*
echo weatherAPI(30.5321,105.2323);
*/
?>


<!--
{
    "weatherinfo": {
        "city": "哈尔滨", // 城市中文名
        "city_en": "haerbin", // 城市英文名
        "date_y": "2012年8月18日", // 发布日期
        "date": "", // ?
        "week": "星期六", // 周信息
        "fchh": "18", // 信息发布时的整点小时数
        "cityid": "101050101", // 城市ID
        "temp1": "18℃~26℃", // 今日气温
        "temp2": "17℃~29℃", // 明日气温
        "temp3": "18℃~23℃", // 第三日气温
        "temp4": "13℃~24℃", // 第四日气温
        "temp5": "15℃~31℃", // 第五日气温
        "temp6": "14℃~32℃", // 第六日气温
        "tempF1": "64.4℉~78.8℉", // 今日气温（华氏）
        "tempF2": "62.6℉~84.2℉", // 明日气温（华氏）
        "tempF3": "64.4℉~73.4℉", // 第三日气温（华氏）
        "tempF4": "55.4℉~75.2℉", // 第四日气温（华氏）
        "tempF5": "59℉~87.8℉", // 第五日气温（华氏）
        "tempF6": "57.2℉~89.6℉", // 第六日气温（华氏）
        "weather1": "多云", // 今日天气
        "weather2": "晴转多云", // 明日天气
        "weather3": "雷阵雨转小雨", // 第三日天气
        "weather4": "多云", // 第四日天气
        "weather5": "晴", // 第五日天气
        "weather6": "晴", // 第六日天气
        "img1": "1", // 天气图标编号，此处的编号及其图片获取规则尚不清楚，如有知道详情的恳请评论告知，我将添加说明
        "img2": "99", // 天气图标编号
        "img3": "0", // 天气图标编号
        "img4": "1", // 天气图标编号
        "img5": "4", // 天气图标编号
        "img6": "7", // 天气图标编号
        "img7": "1", // 天气图标编号
        "img8": "99", // 天气图标编号
        "img9": "0", // 天气图标编号
        "img10": "99", // 天气图标编号
        "img11": "0", // 天气图标编号
        "img12": "99", // 天气图标编号
        "img_single": "1", // ? 可能是天气图标编号
        "img_title1": "多云", // ? 可能是天气图标对应的 title
        "img_title2": "多云", // ? 可能是天气图标对应的 title
        "img_title3": "晴", // ? 可能是天气图标对应的 title
        "img_title4": "多云", // ? 可能是天气图标对应的 title
        "img_title5": "雷阵雨", // ? 可能是天气图标对应的 title
        "img_title6": "小雨", // ? 可能是天气图标对应的 title
        "img_title7": "多云", // ? 可能是天气图标对应的 title
        "img_title8": "多云", // ? 可能是天气图标对应的 title
        "img_title9": "晴", // ? 可能是天气图标对应的 title
        "img_title10": "晴", // ? 可能是天气图标对应的 title
        "img_title11": "晴", // ? 可能是天气图标对应的 title
        "img_title12": "晴", // ? 可能是天气图标对应的 title
        "img_title_single": "多云", // ? 可能是天气图标对应的 title
        "wind1": "西南风小于3级转西风3-4级", // 今日风向风力信息
        "wind2": "西风小于3级转西南风3-4级", // 明日风向风力信息
        "wind3": "西南风小于3级转3-4级", // 第三日风向风力信息
        "wind4": "西南风小于3级转3-4级", // 第四日风向风力信息
        "wind5": "西南风小于3级转3-4级", // 第五日风向风力信息
        "wind6": "西南风小于3级转3-4级", // 第六日风向风力信息
        "fx1": "西南风", // ? 
        "fx2": "西风", // ? 
        "fl1": "小于3级转3-4级", // 今日风力信息
        "fl2": "小于3级转3-4级", // 明日风力信息
        "fl3": "小于3级转3-4级", // 第三日风力信息
        "fl4": "小于3级转3-4级", // 第四日风力信息
        "fl5": "小于3级转3-4级", // 第五日风力信息
        "fl6": "小于3级转3-4级", // 第六日风力信息
        "index": "热",
        "index_d": "天气较热，建议着短裙、短裤、短套装、T恤等夏季服装。年老体弱者宜着长袖衬衫和单裤。",
        "index48": "炎热",
        "index48_d": "天气炎热，建议着短衫、短裙、短裤、薄型T恤衫、敞领短袖棉衫等清凉夏季服装。",
        "index_uv": "中等", // 紫外线信息
        "index48_uv": "弱", // 48 小时紫外线信息
        "index_xc": "较适宜", // 洗车指数
        "index_tr": "适宜", // 旅游指数
        "index_co": "舒适", // 舒适指数
        "st1": "25",
        "st2": "17",
        "st3": "28",
        "st4": "19",
        "st5": "18",
        "st6": "16",
        "index_cl": "较适宜", // 晨练指数
        "index_ls": "适宜", // 晾晒指数
        "index_ag": "极易发" // 感冒指数
    }
}
-->