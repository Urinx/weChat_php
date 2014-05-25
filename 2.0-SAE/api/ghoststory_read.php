<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no,target-densitydpi=high-dpi">
<link rel="stylesheet" media="screen and (orientation:portrait) and (min-width:960px)">

<style type="text/css">
body{
	background: black;
	color: white;
	font-size: 1.8em;
	font-weight: bolder;
}
a{
	color: gray;
}
</style>
<?php
header('Content-type:text/html;charset=utf-8');
$res=file_get_contents($_GET['url']);
$res=iconv('gb2312','utf-8',$res);
//$res=preg_replace('#<div class=\'\w\w\w\d\d\d\'>.*?</div>#s','',$res);
preg_match_all( '#<div class="arzw">(.*?)</div>\s*<div#s',$res,$arzw);
preg_match_all( '/共(\d)页:/s',$arzw[1][0],$n);

$del=array('#<script.*?></script>#s','#<div class="jogger">.+#s','#dedecms.com#s','#织梦好，好织梦#s','#本文来自织梦#s','#copyright dedecms#s','#内容来自dedecms#s','#织梦内容管理系统#s');
$article=preg_replace($del,'',$arzw[1][0]);
$article=preg_replace('<div class="arwzks">','hr',$article);

echo $article;
echo '<hr>';

if(isset($n[1][0])){
	preg_match_all( '#(.*?)(\d+)\.html#s',$_GET['url'],$m);
	if ((int)$m[2][0]<(int)$n[1][0]) {
		$next=$_SERVER['PHP_SELF'].'?url='.$m[1][0].((int)$m[2][0]+1).'.html';
		echo '共'.$n[1][0].'页 <a href="'.$next.'">下一页</a>';
	}
	elseif ($m[2][0]==$n[1][0]) {
		echo '共'.$n[1][0].'页 完';
	}
	else{
		$next=$_SERVER['PHP_SELF'].'?url='.$m[1][0].$m[2][0].'_2.html';
		echo '共'.$n[1][0].'页 <a href="'.$next.'">下一页</a>';
	}
}
else{
	echo '完';
}
?>