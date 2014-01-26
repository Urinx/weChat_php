<?php
header('Content-type:text/html;charset=utf-8');
function wooyun(){
    $data=array();
    $url='http://www.wooyun.org/feeds/submit';
    $result=file_get_contents($url);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
    
    for ($i=0; $i < 6; $i++) {
        $reg='#<strong>简要描述：</strong><br/>(.+)<br/><strong>详细说明#i';
        preg_match_all($reg,$xml->channel->item[$i]->description, $matches);
    	array_push($data,array('title'=>$xml->channel->item[$i]->title,'description'=>'简要描述：'.$matches[1][0],'link'=>$xml->channel->item[$i]->link));
    }

    return $data;
}

print_r(wooyun());

?>