<?php
header("content-Type: text/html; charset=utf-8");

function doubanMovies($word){
	$list=array('top10'=>'top250','北美票房榜'=>'us_box','口碑榜'=>'weekly','新片榜'=>'new_movies','正在上映'=>'nowplaying','即将上映'=>'coming');
	$doubanMovie_api_url='http://api.douban.com/v2/movie/';
	if (!isset($list[$word])) {
	 	$result=file_get_contents($doubanMovie_api_url.'search?q='.$word);
	}
	else{
		$result=file_get_contents($doubanMovie_api_url.$list[$word]);
	}
	$jsondata=json_decode($result);
	$movie=array();
	//top250,search,us_box
	foreach ($jsondata->subjects as $value) {
		if ($list[$word]=='us_box') {
			$value=$value->subject;
		}
		array_push($movie, array('average'=>$value->rating->average,'title'=>$value->title,'original_title'=>$value->original_title,'year'=>$value->year,'images'=>$value->images,'alt'=>$value->alt,'id'=>$value->id));
	}
	return $movie;//{average,title,original_title,year,images,alt,id}
}

function doubanMovie($id){
	$doubanMovie_api_url='http://api.douban.com/v2/movie/subject/';
	$result=file_get_contents($doubanMovie_api_url.$id);
	$jsondata=json_decode($result);
	$movie=array('average'=>$jsondata->rating->average,'year'=>$jsondata->year,'images'=>$jsondata->images->large,'mobile_url'=>$jsondata->mobile_url,'title'=>$jsondata->title,'genres'=>$jsondata->genres,'countries'=>$jsondata->countries,'summary'=>$jsondata->summary,'aka'=>$jsondata->aka,'directors'=>$jsondata->directors,'casts'=>$jsondata->casts);
	return $movie;
}
$m=doubanMovie(1764796);
print_r($m['directors'][0]->avatars);
?>