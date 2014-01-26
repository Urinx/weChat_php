<?php
header('Content-type:text/html;charset=utf-8');
function freebuf(){
    $data=array();
    $url='http://www.freebuf.com/feed';
    $result=file_get_contents($url);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
    
    for ($i=0; $i < 6; $i++) {
    	array_push($data,array('title'=>$xml->channel->item[$i]->title,'description'=>htmlspecialchars_decode($xml->channel->item[$i]->description),'link'=>$xml->channel->item[$i]->link));
    }

    return $data;
}

print_r(freebuf());

?>