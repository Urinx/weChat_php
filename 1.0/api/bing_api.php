<?php
//type:Image,Web
/*
Web:
{
	"__metadata":{
		"uri":"https://api.datamarket.azure.com/Data.ashx/Bing/Search/Web?Query=\u0027dudulu\u0027&$skip=0&$top=1",
		"type":"WebResult"
	},
	"ID":"811e38c3-1463-483a-b787-e34e9826f960",
	"Title":"User Dudulu, from Germany",
	"Description":"Profile of user Dudulu, from Germany at Postcrossing.com - The Traveling Postcards Project",
	"DisplayUrl":"www.postcrossing.com/user/Dudulu",
	"Url":"http://www.postcrossing.com/user/Dudulu"
}
*/
function bing($query,$type){
	// Replace this value with your account key
	$accountKey = '************************************';

	$ServiceRootURL = 'https://api.datamarket.azure.com/Bing/Search/';
	$WebSearchURL = $ServiceRootURL.$type.'?$format=json&Query=';
	$context = stream_context_create(
		array(
		'http' => array(
			'request_fulluri' => true,
			'header'  => "Authorization: Basic ".base64_encode($accountKey.":".$accountKey)
			)
		));
	$request = $WebSearchURL.urlencode('\''.$query.'\'');

	$response = file_get_contents($request, 0, $context);
	$jsonobj = json_decode($response);

	$arr=array();
	if ($type=='Image') {
		foreach($jsonobj->d->results as $value){
			array_push($arr, $value->Thumbnail->MediaUrl);
		}
	}
	elseif($type=='Web'){
		foreach($jsonobj->d->results as $value){
			array_push($arr, array('title'=>$value->Title,'description'=>$value->Description,'url'=>$value->Url));
		}
	}
	return $arr;
}

//$image_url=bing('loli','Image');
//print_r($image_url);
$web=bing('dudulu','Web');
print_r($web[0]['title']);
?>