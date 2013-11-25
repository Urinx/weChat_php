<?php
header("content-Type: text/html; charset=utf-8");

function baike($word){
	$baike_api_url='http://www.baidu.com/s?wd='.$word.'%20百度百科';
	$result=file_get_contents($baike_api_url);

	$reg='#data="{title : \'.+\', link : \'(.+)\'}"#i';
	if (preg_match_all($reg, $result, $matches)) {
	 	$baike_url=$matches[1][0];
	 }
	elseif (preg_match_all('#mu="(.+)" data-op=#i', $result, $matches)) {
		$baike_url=$matches[1][0];
	}
	elseif (preg_match_all('#<a href="(http://www.baidu.com/link\?url=.+)" target="_blank"#i', $result, $matches)){
		$baike_url=$matches[1][0];
	}
	else{
		exit('404');
	}

	$baike=file_get_contents($baike_url);
	if (preg_match_all('#<title>(.+)</title>#i', $baike, $baike_title)) {
		$title=$baike_title[1][0];
	}
	if (preg_match_all('#<meta name="Description" content="(.+)" /><meta#i', $baike, $baike_description)) {
		$description=$baike_description[1][0];
		$description=str_replace(array('&amp;#91;','&amp;#93;'), array('[',']'), $description);
	}

	$baikes=array('title'=>$title,'description'=>$description,'url'=>$baike_url);
	return $baikes;
}

$baike=baike('Euler');
echo($baike['description']);
?>