<?php
header("content-Type: text/html; charset=utf-8");

$word='Urinx';
$qrcode_api_url='http://chart.apis.google.com/chart?cht=qr&chs=400x400&choe=UTF-8&chl='.$word;
echo('<img src="'.$qrcode_api_url.'">');
?>