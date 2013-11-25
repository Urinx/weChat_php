<?php
header("content-Type: text/html; charset=Utf-8");

function simsimi($word){
	$key='***********************************';
	$simsimi_api_url='http://sandbox.api.simsimi.com/request.p?key='.$key.'&lc=ch&ft=0.0&text='.$word;
	$simjson=file_get_contents($simsimi_api_url);
	$simsimi=json_decode($simjson,1);

	if($simsimi['result']=='100') {
			return $simsimi['response'];
	}
	elseif ($simsimi['result']=='400') {
		return '400-'.$simsimi['msg'];
	}
	elseif ($simsimi['result']=='401') {
		return '401-'.$simsimi['msg']."\n".'看来小u的Trial-key到期了，快提醒我吧。';
	}
	elseif ($simsimi['result']=='404') {
		return '404-'.$simsimi['msg']."\n".'这也能遇上404!!';
	}
	elseif ($simsimi['result']=='500') {
		return '500-'.$simsimi['msg']."\n".'服务器出问题，小u表示无能为力。';
	}
	else{
		return '小u还不会回答这个问题的说...';
	}
}

echo(simsimi('你好'));
?>