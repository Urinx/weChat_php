<?php
header("content-Type: text/html; charset=Utf-8");

function en_sentenceAPI(){
	$en_api_url='http://dict.hjenglish.com/rss/daily/en';
	$en_output=file_get_contents($en_api_url);
	$enxml=simplexml_load_string($en_output);

	$en_sentence=$enxml->channel->item->en_sentence."\n";
	$en_sentence.=$enxml->channel->item->cn_sentence."\n";
	$en_sentence.='Update time:'.$enxml->channel->pubDate."\n";
	$en_sentence.='<a href="'.$enxml->channel->item->flashsound.'">朗读</a>';
	return $en_sentence;
}
echo str_replace("\n", '<br>', en_sentenceAPI());
?>