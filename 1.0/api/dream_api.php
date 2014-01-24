<?php
header("content-Type: text/html; charset=utf-8");

function dream($word){
	$dream_api_url='http://api2.sinaapp.com/search/dream/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.urlencode($word);
	$result=file_get_contents($dream_api_url);
	$dream=json_decode($result);
	return $dream->text->content;
}
echo dream('神');
?>