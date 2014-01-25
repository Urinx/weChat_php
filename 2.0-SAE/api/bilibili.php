<?php
header('Content-type:text/html;charset=utf-8');
function bilibili(){
    $data=array();
    $category=array('1'=>'动画','3'=>'音乐','4'=>'游戏','5'=>'娱乐','11'=>'专辑','13'=>'新番连载');
    foreach ($category as $key=>$value){
        $url='http://www.bilibili.tv/rss-'.$key.'.xml';
        $result=file_get_contents($url);
    	$xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
        array_push($data,array('title'=>$xml->channel->item->title[0],'description'=>$xml->channel->item->description[0],'category'=>$value,'link'=>$xml->channel->item->link[0]));
    }
    return $data;
}

print_r(bilibili());

?>
