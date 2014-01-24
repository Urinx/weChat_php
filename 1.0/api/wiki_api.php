<?php
header("content-Type: text/html; charset=utf-8");
//url=>http://zh.wikipedia.org/wiki/title
//China:zh;English:en

function wiki($srsearch,$lng){
	$wiki_api_url='http://'.$lng.'.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&format=xml&srsearch='.$srsearch;
	$result=file_get_contents($wiki_api_url);
	$xmldata=simplexml_load_string($result);

	$arr=array();
	foreach ($xmldata->query->search->p as $value) {
		$value['snippet']=str_replace(array('<span class=\'searchmatch\'>','</span>',' '), '', $value['snippet']);
		array_push($arr,array('title'=>$value['title'],'snippet'=>$value['snippet']));
	}
	return $arr;
}

print_r(wiki('人人','zh'));
?>