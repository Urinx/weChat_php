<?php
function stackoverflow(){
    $num=5;
    $stack_url='http://stackoverflow.com/';
    $result=file_get_contents($stack_url);
    
    $mainreg='#<div[\s]+onclick="window.location.href=\'/questions/(\d+/.+)\'"[\s]+class="cp"[\s]*>#i';
    $votereg='#<div class="mini-counts">(\d+)</div>[\s]+<div>votes</div>#i';
    $answerreg='#<div class="mini-counts">(\d+)</div>[\s]+<div>answers</div>#i';
    $viewreg='#<div class="mini-counts">(\d+)</div>[\s]+<div>view[s]*</div>#i';
    
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
    return $data;
}

function test(){
    return file_get_contents('http://stackoverflow.com/questions/20988299/');
}

print_r(stackoverflow());

?>
