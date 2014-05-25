<?php
header('Content-type:text/html;charset=utf-8');
$ghost=array('短篇鬼故事'=>1,'长篇鬼故事'=>2,'校园鬼故事'=>3,'医院鬼故事'=>4,'家里鬼故事'=>5,
    '民间鬼故事'=>6,'灵异事件'=>7,'听鬼故事'=>10,'中国灵异'=>25,'灵异知识库'=>26,'推理小说'=>46);

function ghost_story($w='灵异知识库'){
    global $ghost;
    $data=array();
    $url='http://www.gui44.com/data/rss/'.$ghost[$w].'.xml';
    $xml=simplexml_load_file($url,'SimpleXMLElement',LIBXML_NOCDATA);

    if($xml){
        for ($i=0; $i < 6; $i++) {
            $j=$xml->channel->item[$i];
            array_push($data,array('title'=>$j->title,'description'=>$j->description,'link'=>$j->link,'pubDate'=>$j->pubDate,'author'=>$j->author));
        }
    }
    else{
        $result=file_get_contents($url);
        $result=iconv('gb2312','gbk',$result);
        $result=iconv('gbk','utf-8',$result);
        preg_match_all( "/\<item\>(.*?)\<\/item\>/s",$result,$items);
        for ($i=0; $i < 6; $i++){
            preg_match_all( "/\<title\>\<\!\[CDATA\[(.*?)\]\]\>\<\/title\>/",$items[0][$i],$title);
            $title=$title[1][0];
            preg_match_all( "/\<description\>\<\!\[CDATA\[(.*?)\]\]\>\<\/description\>/",$items[0][$i],$description);
            $description=$description[1][0];
            preg_match_all( "/\<link\>(.*?)\<\/link\>/",$items[0][$i],$link);
            $link=$link[1][0];
            preg_match_all( "/\<pubDate\>(.*?)\<\/pubDate\>/",$items[0][$i],$pubDate);
            $pubDate=$pubDate[1][0];
            preg_match_all( "/\<author\>(.*?)\<\/author\>/",$items[0][$i],$author);
            $author=$author[1][0];
            array_push($data,array('title'=>$title,'description'=>$description,'link'=>$link,'pubDate'=>$pubDate,'author'=>$author));
        }
    }
    
    return $data;
}

function zhangzhen($i){
    $xml=simplexml_load_file('http://www.gui44.com/data/rss/10.xml','SimpleXMLElement',LIBXML_NOCDATA);
    preg_match_all('#flashvars="son=(.*?)&amp;autoplay=1&amp;autoreplay=0"#s',file_get_contents($xml->channel->item[$i]->link),$u);
    $title=str_replace(array('张震讲鬼故事系列之','张震讲鬼故事系列','张震鬼故事之','张震讲鬼故事'), '',$xml->channel->item[$i]->title);
    return array('title'=>$title,'description'=>'张震','musicurl'=>$u[1][0],'HQmusicurl'=>$u[1][0]);
}

//print_r(ghost_story());
print_r(zhangzhen((int)'0'));

?>