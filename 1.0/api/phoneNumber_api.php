<?php
header("content-Type: text/html; charset=utf-8");

function phoneNumber($num){
	$phoneNum_api_url='http://cz.115.com/?ct=index&ac=get_mobile_local&mobile='.$num;
	$result=str_replace(array('(',')'), '', file_get_contents($phoneNum_api_url));
	$phone=json_decode($result);
	$phoneNum=array('province'=>$phone->province,'city'=>$phone->city,'corp'=>$phone->corp);
	return $phoneNum;
}
print_r(phoneNumber('18202750298'));
?>