<?php
header("content-Type: text/html; charset=utf-8");

function baiduNews(){
	$word='最新';
	$num=5;
	$baidunews_api_url='http://news.baidu.com/ns?tn=newsfcu&from=news&cl=2&ct=0&rn='.$num.'&word='.$word;
	$result=file_get_contents($baidunews_api_url);
	$reg='#<a[\s]+href="(?<url>[^\s>]+)"[^>]*target="_blank">(?<title>[^>]+)</a>&nbsp;<span>(?<resrc>[^>]+)</span>#i';
	preg_match_all($reg, $result, $matches);
	$matches[resrc]=str_replace('&nbsp;',' ',$matches[resrc]);
	for ($i=0; $i < 5; $i++) { 
		$matches[title][$i]=iconv('gbk','utf-8',$matches[title][$i]);
		$matches[resrc][$i]=iconv('gbk','utf-8',$matches[resrc][$i]);
	}
	return $matches;
}
print_r(baiduNews());//url,title,resrc
?>