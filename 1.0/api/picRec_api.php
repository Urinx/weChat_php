<?php
header("content-Type: text/html; charset=utf-8");

function picRec($url){
	$picRec_api_url='http://api2.sinaapp.com/recognize/picture/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.$url;
	$result=file_get_contents($picRec_api_url);
	$pic=json_decode($result);
	return $pic->text->content;
}

echo picRec('http://img.hb.aicdn.com/1fbb370153a4948d922d206c2acd608e7ea42b0c2fb27-8lGtP5_fw580');
?>