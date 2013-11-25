<?php
header("content-Type: text/html; charset=utf-8");

function jokes(){
	$jokes_api_url='http://www.djdkx.com/open/randxml';
	$result=file_get_contents($jokes_api_url);
	$xmldata=simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA);
	$xmldata->content=str_replace(array('&nbsp;','<br/>'), '', trim($xmldata->content));
	return $xmldata->content;
}
echo jokes();
?>