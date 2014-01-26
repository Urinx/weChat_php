<?php
header('Content-type:text/html;charset=utf-8');
function shejidaren(){
    $data=array();
    $url='http://www.shejidaren.com/feed';
    $result=file_get_contents($url);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
    
    for ($i=0; $i < 6; $i++) {
        $reg='#<img src="(http://images\.shejidaren\.com/wp-content/uploads/.+)"[\s]+class#i';
        preg_match_all($reg,$xml->channel->item[$i]->description,$matches);
    	array_push($data,array('title'=>$xml->channel->item[$i]->title,'cover'=>$matches[1][0],'link'=>$xml->channel->item[$i]->link));
    }

    return $data;
}

print_r(shejidaren());

?>