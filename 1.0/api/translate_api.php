<?php
header("content-Type: text/html; charset=Utf-8");

function translateAPI($words){
	$data=$words."\n";
	$words=urlencode($words);

	$keyform='******';
	$key='*****';
	$fanyi_api_url='http://fanyi.youdao.com/openapi.do?keyfrom='.$keyform.'&key='.$key.'&type=data&doctype=json&version=1.1&q=';
	$transjson=file_get_contents($fanyi_api_url.$words);
	$translated=json_decode($transjson,1);
	$errorcode=$translated['errorCode'];

	if (isset($errorcode)) {
		switch ($errorcode) {
			case 0:
				$data.='翻译:'.$translated['translation'][0]."\n";
				$data.='基本词典:'."\n";
				$data.='读音:['.$translated['basic']['phonetic'].']'."\n";

				foreach ($translated['basic']['explains'] as $value) {
					$explains.='&nbsp;'.$value."\n";
				}
				$data.='解释:'."\n".$explains."\n";//

				$data.='网络释义:'."\n";
				foreach ($translated['web'] as $arr1) {
					$webdata.=$arr1['key']."\n";
					foreach ($arr1['value'] as $value) {
						$webdata.=$value.' ';
					}
					$webdata.="\n";
				}
				$data.=$webdata;
				break;

			case 20:
				$data='要翻译的文本过长';
				break;
			
			case 30:
				$data='无法进行有效的翻译';
				break;

			case 40:
				$data='不支持的语言类型';
				break;

			case 50:
				$data='无效的key';
				break;

			default:
				$data='出现异常';
				break;
		}
	}
	return $data;
}
echo str_replace("\n", '<br>', translateAPI('词典'));
?>