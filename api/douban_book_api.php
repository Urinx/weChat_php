<?php
header("content-Type: text/html; charset=utf-8");

function doubanBooks($word){
	$doubanBook_api_url='https://api.douban.com/v2/book/search?count=5&q=';
	$result=file_get_contents($doubanBook_api_url.$word);
	$jsondata=json_decode($result);
	$book=array();
	foreach ($jsondata->books as $value) {
		array_push($book, array('average'=>$value->rating->average,'author'=>$value->author,'pubdate'=>$value->pubdate,'images'=>$value->images,'alt'=>$value->alt,'title'=>$value->title,'publisher'=>$value->publisher,'summary'=>$value->summary,'price'=>$value->price,'tags'=>$value->tags,'id'=>$value->id));
	}
	return $book;
}

function doubanBook($id){
	$doubanBook_api_url='https://api.douban.com/v2/book/';
	$result=file_get_contents($doubanBook_api_url.$id);
	$jsondata=json_decode($result);
	$book=array('average'=>$jsondata->rating->average,'author'=>$jsondata->author,'pubdate'=>$jsondata->pubdate,'images'=>$jsondata->images,'alt'=>$jsondata->alt,'title'=>$jsondata->title,'publisher'=>$jsondata->publisher,'summary'=>$jsondata->summary,'price'=>$jsondata->price,'tags'=>$jsondata->tags);
	return $book;
}
$m=doubanBooks('日本文学');
print_r($m);
?>