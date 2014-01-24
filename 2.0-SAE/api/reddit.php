<?php
function reddit(){
    $num=5;
    $stack_url='http://www.reddit.com/';
    $result=file_get_contents($stack_url);
    
    $mainreg='#<a[\s]+class="title[\s]*"[\s]+href="(\S+)" tabindex="1" [A-Za-z""=\s]*>[A-Za-z\s]+</a>#i';
    $title='#>[A-Za-z\s]+</a>#i';
    $votereg='#<div class="score unvoted">(\d+)</div>#i';
    $imgreg='#<img[\s]+src="(http://[a-z].thumbs.redditmedia.com/\S+)"[\s]+width=\'\d+\'[\s]+height=\'\d+\'[\s]+alt=""/>#i';
    //$imgreg='#http://[a-z].thumbs.redditmedia.com/(\S+)#i';
    $viewreg='#<div class="mini-counts">(\d+)</div>[\s]+<div>view[s]*</div>#i';
    /*
    $arr=array($mainreg,$votereg,$answerreg,$viewreg);
    $res=array();
    $data=array();
    
    foreach ($arr as $reg){
    	preg_match_all($reg, $result, $matches);
        array_push($res,$matches[1]);
    }
    
    for ($i=0; $i < 5; $i++) {
        list($id,$title)=preg_split('#/#',$res[0][$i]);
        array_push($data,array('id'=>$id,'title'=>$title,'vote'=>$res[1][$i],'answer'=>$res[2][$i],'view'=>$res[3][$i]));
    }
    return $data;*/
    preg_match_all($mainreg, $result, $matches);
    return $matches;
}

print_r(reddit());

?>
