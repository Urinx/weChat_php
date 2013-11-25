<?php
header("content-Type: text/html; charset=utf-8");

function lotterySearch($word){
	$lottery_api_url='http://api2.sinaapp.com/search/lottery/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.urlencode($word);
	$result=file_get_contents($lottery_api_url);
	$lottery=json_decode($result);
	return $lottery->text->content;
}
function lottery(){
	$lotteryArray=array('双色球','七乐彩','大乐透','七星彩','排列3','排列5','胜负彩','六场半全场');
	foreach ($lotteryArray as $value) {
		$lottery.=lotterySearch($value)."\n";
	}
	return $lottery;
}

$lottery=lottery();
print_r($lottery);
?>