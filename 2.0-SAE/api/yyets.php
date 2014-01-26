<?php
header('Content-type:text/html;charset=utf-8');
function yyets(){
    $data=array();
    $url='http://www.yyets.com/rss/feed/';
    $result=file_get_contents($url);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
    
    for ($i=0; $i < 6; $i++) {
        $r=file_get_contents($xml->channel->item[$i]->link);
        $reg='#<img[\s]+src="(http://res\.yyets\.com/ftp/.+\.jpg)"[\s]+/>#i';
        preg_match_all($reg, $r, $matches);
        
        array_push($data,array('title'=>$xml->channel->item[$i]->title,'description'=>$xml->channel->item[$i]->description,'cover'=>$matches[1][0],'link'=>$xml->channel->item[$i]->link));
    }

    return $data;
}

print_r(yyets());

?>