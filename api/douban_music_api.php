<?php
header("content-Type: text/html; charset=utf-8");

function doubanMusics($word){
	$doubanMusic_api_url='https://api.douban.com/v2/music/search?count=5&q=';
	$result=file_get_contents($doubanMusic_api_url.$word);
	$jsondata=json_decode($result);
	$music=array();
	foreach ($jsondata->musics as $value) {
		array_push($music, array('average'=>$value->rating->average,'author'=>$value->author,'pubdate'=>$value->attrs->pubdate,'image'=>$value->image,'alt'=>$value->alt,'title'=>$value->title,'publisher'=>$value->attrs->publisher,'singer'=>$value->attrs->singer,'version'=>$value->attrs->version,'id'=>$value->id));
	}
	return $music;
}

function doubanMusic($id){
	$doubanMusic_api_url='https://api.douban.com/v2/music/';
	$result=file_get_contents($doubanMusic_api_url.$id);
	$jsondata=json_decode($result);
	$music=array('average'=>$jsondata->rating->average,'author'=>$jsondata->author,'summary'=>$jsondata->summary,'image'=>$jsondata->image,'mobile_link'=>$jsondata->mobile_link,'title'=>$jsondata->title,'id'=>$jsondata->id,'publisher'=>$jsondata->attrs->publisher,'singer'=>$jsondata->attrs->singer,'version'=>$jsondata->attrs->version,'pubdate'=>$jsondata->attrs->pubdate,'tags'=>$jsondata->tags);
	return $music;
}
$m=doubanMusic(25722147);
print_r($m);
?>