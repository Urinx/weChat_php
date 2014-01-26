<?php
header('Content-type:text/html;charset=utf-8');
function ifanr(){
    $data=array();
    $url='http://www.ifanr.com/feed';
    $result=file_get_contents($url);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
    
    for ($i=0; $i < 6; $i++) {
        $reg='#(.+)题图来自#i';
        preg_match_all($reg, $xml->channel->item[$i]->description, $matches);
    	array_push($data,array('title'=>$xml->channel->item[$i]->title,'description'=>$matches[1],'cover'=>$xml->channel->item[$i]->image,'link'=>$xml->channel->item[$i]->link));
    }

    return $data;
}

print_r(ifanr());

?>