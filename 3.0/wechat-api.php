<?php

class w {
    //响应请求
    static function response($xml,$data,$type='text') {
        $time=time();
        $xmltpl['text']='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName>';
        $xmltpl['text'].='<CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>';
        $xmltpl['item']='<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>';
        $xmltpl['news']='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType>';
        $xmltpl['news'].='<ArticleCount>%s</ArticleCount><Articles>%s</Articles><FuncFlag>1</FuncFlag></xml>';
        $xmltpl['music']='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType>';
        $xmltpl['music'].='<Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl></Music></xml>';
        $xmltpl['image']='<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><PicUrl><![CDATA[%s]]></PicUrl><MsgId>1234567890123456</MsgId></xml>';
        
        if($type == 'text'){
            return sprintf($xmltpl['text'],$xml->FromUserName,$xml->ToUserName,$time,$data);
        }

        elseif($type=='news'){
            if(is_array($data)){
                $items='';
                if(count($data) > 1){
                    foreach($data as $e){
                        $title=trim($e['title']."\n".$e['note'],"\n");
                        $items.=sprintf($xmltpl['item'],$title,'',$e['cover'],$e['link']);
                    }
                }
                elseif(count($data) == 1){
                    foreach($data as $e){
                        $items=sprintf($xmltpl['item'],$e['title'],$e['note'],$e['cover'],$e['link']);
                    }
                }
                else{
                    return self::response($xml,'没有数据');
                }
                return sprintf($xmltpl['news'],$xml->FromUserName,$xml->ToUserName,$time,count($data),$items);
            }
            return self::response($xml,'没有数据');
        }

        elseif($type == 'music'){
                return sprintf($xmltpl['music'],$xml->FromUserName,$xml->ToUserName,$time,$data['title'],$data['description'],$data['musicurl'],$data['HQmusicurl']);
        }

        //这个目前不能用
        elseif($type == 'image'){
                return sprintf($xmltpl['image'],$xml->FromUserName,$xml->ToUserName,$time,$data);
        }

        return self::response($xml,'主人不在家，如果有什么事的话，你可以直接对[小u]说。');
    }

    /**
    **已知两点的经纬度，计算两点间的距离(公里)
    **/
    static function getDistance($lat1,$lng1,$lat2,$lng2){
        $lat1=(double)$lat1;
        $lat2=(double)$lat2;
        $lng1=(double)$lng1;
        $lng2=(double)$lng2;
        $EARTH_RADIUS=6378.137;
        $radLat1=$lat1*pi()/180.0;
        $radLat2=$lat2*pi()/180.0;
        $a=$radLat1-$radLat2;
        $b=$lng1*pi()/180.0-$lng2*pi()/180.0;
        $s=2*asin(sqrt(pow(sin($a/2),2)+
        cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s=$s*$EARTH_RADIUS;
        $s=round($s*10000)/10000;
        return number_format($s,2,'.','');
    }

    static function requestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    static function isGET() {
        return self::requestMethod() == 'GET';
    }

    static function isPOST() {
        return self::requestMethod() == 'POST';
    }

    /**
     * POST 请求指定URL
     */
    static function fpost($url, $post = '', $limit = 0, $cookie = '', $ip = '', $timeout = 15, $block = TRUE) {
        $return='';
        $matches=parse_url($url);
        !isset($matches['host']) && $matches['host'] = '';
        !isset($matches['path']) && $matches['path'] = '';
        !isset($matches['query']) && $matches['query'] = '';
        !isset($matches['port']) && $matches['port'] = '';
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;
        if($post) {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: '.strlen($post)."\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        }
        else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }

        if(function_exists('fsockopen')) {
            $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        }
        elseif (function_exists('pfsockopen')) {
            $fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        }
        else {
            $fp = false;
        }

        if(!$fp) {
            return '';
        }
        else {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);
            if(!$status['timed_out']) {
                while (!feof($fp)) {
                    if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
                        break;
                    }
                }

                $stop = false;
                while(!feof($fp) && !$stop) {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if($limit) {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);
            return $return;
        }
    }

}

function weatherAPI($Location_X,$Location_Y){
    $map_api_url='http://api.map.baidu.com/geocoder?coord_type=wgs84&location='.$Location_X.','.$Location_Y;
    $output=file_get_contents($map_api_url);
    $mapxml=simplexml_load_string($output);
    $province=str_replace('省','',$mapxml->result->addressComponent->province);
    $city=str_replace(array('市','县','区'),array('','',''),$mapxml->result->addressComponent->city);

    if(file_exists('CityID.xml')){
        $wxml=simplexml_load_file('CityID.xml');
        for ($i=0; $i < 34; $i++) { 
            if($province==$wxml->Province[$i][Name]){
                for ($j=0; $j < count($wxml->Province[$i]->City); $j++) { 
                    if ($city==$wxml->Province[$i]->City[$j][Name]) {
                        $cityID=$wxml->Province[$i]->City[$j][ID];
                    }
                }
            }
        }
    }
    else{}

    $weather_api_url='http://m.weather.com.cn/data/'.$cityID.'.html';
    $weather=file_get_contents($weather_api_url);
    $wea=json_decode($weather,1);

    $weaData=$wea['weatherinfo']['city']."\n";
    $weaData.='发布日期:'.$wea['weatherinfo']['date_y'].' '.$wea['weatherinfo']['week']."\n";
    $weaData.='今日气温:'.$wea['weatherinfo']['temp1'].'/'.$wea['weatherinfo']['tempF1']."\n";
    $weaData.='天气:'.$wea['weatherinfo']['weather1']."\n";
    $weaData.='状态:'.$wea['weatherinfo']['index']."\n";
    $weaData.='建议:'.$wea['weatherinfo']['index_d']."\n";
    $weaData.='风向风力:'.$wea['weatherinfo']['wind1']."\n";
    $weaData.='紫外线:'.$wea['weatherinfo']['index_uv']."\n";
    $weaData.='洗车指数:'.$wea['weatherinfo']['index_xc']."\n";
    $weaData.='旅游指数:'.$wea['weatherinfo']['index_tr']."\n";
    $weaData.='舒适指数:'.$wea['weatherinfo']['index_co']."\n";
    $weaData.='晨练指数:'.$wea['weatherinfo']['index_cl']."\n";
    $weaData.='晾晒指数:'.$wea['weatherinfo']['index_ls']."\n";
    $weaData.='感冒指数:'.$wea['weatherinfo']['index_ag']."\n";
    $weaData.="\n".'未来几天天气'."\n";
    $weaData.='明日:'.$wea['weatherinfo']['temp2'].'/'.$wea['weatherinfo']['tempF2']."\n";
    $weaData.=$wea['weatherinfo']['weather2']."\n";
    $weaData.=$wea['weatherinfo']['wind2']."\n";
    $weaData.='第三日:'.$wea['weatherinfo']['temp3'].'/'.$wea['weatherinfo']['tempF3']."\n";
    $weaData.=$wea['weatherinfo']['weather3']."\n";
    $weaData.=$wea['weatherinfo']['wind3']."\n";
    $weaData.='第四日:'.$wea['weatherinfo']['temp4'].'/'.$wea['weatherinfo']['tempF4']."\n";
    $weaData.=$wea['weatherinfo']['weather4']."\n";
    $weaData.=$wea['weatherinfo']['wind4']."\n";
    $weaData.='第五日:'.$wea['weatherinfo']['temp5'].'/'.$wea['weatherinfo']['tempF5']."\n";
    $weaData.=$wea['weatherinfo']['weather5']."\n";
    $weaData.=$wea['weatherinfo']['wind5']."\n";
    $weaData.='第六日:'.$wea['weatherinfo']['temp6'].'/'.$wea['weatherinfo']['tempF6']."\n";
    $weaData.=$wea['weatherinfo']['weather6']."\n";
    $weaData.=$wea['weatherinfo']['wind6']."\n";

    return $weaData;
}

function translateAPI($words){
    $data=$words."\n";
    $words=urlencode($words);

    $fanyi_api_url='http://fanyi.youdao.com/openapi.do?keyfrom=urinxs&key=836837635&type=data&doctype=json&version=1.1&q=';
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
                    $explains.=$value."\n";
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

function en_sentenceAPI(){
    $en_api_url='http://dict.hjenglish.com/rss/daily/en';
    $en_output=file_get_contents($en_api_url);
    $enxml=simplexml_load_string($en_output);

    $en_sentence=$enxml->channel->item->en_sentence."\n";
    $en_sentence.=$enxml->channel->item->cn_sentence."\n";
    $en_sentence.='Update time:'.$enxml->channel->pubDate."\n";
    $en_sentence.='<a href="'.$enxml->channel->item->flashsound.'">朗读</a>';
    return $en_sentence;
}

function simsimi($word){
    $s = new SaeStorage();
    $key=$s->read('simsimi','simi.txt');
    $simsimi_api_url='http://sandbox.api.simsimi.com/request.p?key='.$key.'&lc=ch&ft=0.0&text='.$word;
    $simjson=file_get_contents($simsimi_api_url);
    $simsimi=json_decode($simjson,1);

    switch ($simsimi['result']) {
        case '100':
            $backws=$simsimi['response'];
            break;
        
        case '400':
            $backws='400-'.$simsimi['msg'];
            break;

        case '401':
            $backws='401-'.$simsimi['msg']."\n".'小u is sleeping now.请不要吵醒我！';
            break;

        case '404':
            if(preg_match('/。。/i', $word)) {
                $backws='(*^__^*) 嘻嘻……'.'/:B-)';
            }
            else{$backws='404';}
            break;

        case '500':
            $backws='500-'.$simsimi['msg']."\n".'服务器出问题，小u表示无能为力。';
            break;

        default:
            $backws='404';
            break;
    }
    $backws=str_replace(array('贱鸡','小黄鸡','黄鸡','xhjchat'), array('小u','小u','小u','urinx'), $backws);
    if(preg_match('/微信/i',$backws)) {
        $backws='该消息被防火长城,不对。。是被小u消音了，原因你懂的。。保护你的身体，有爱你的健康'.'/:B-)';
        $backws.="\n如果你发现有害身心健康的信息，请及时截图报告小u，小u全力进行封杀。";
    }
    return $backws;
}

function bing($query,$type){
    $accountKey='T7m/69vghK1gjxMTPkGqX5Lfz9fn50YXTc7S6ykGTdY';

    $ServiceRootURL='https://api.datamarket.azure.com/Bing/Search/';
    $WebSearchURL=$ServiceRootURL.$type.'?$format=json&Query=';
    $context=stream_context_create(
        array(
        'http' => array(
            'request_fulluri' => true,
            'header'  => "Authorization: Basic ".base64_encode($accountKey.":".$accountKey)
            )
        ));
    $request=$WebSearchURL.urlencode('\''.$query.'\'');

    $response=file_get_contents($request, 0, $context);
    $jsonobj=json_decode($response);

    $arr=array();
    if ($type=='Image') {
        foreach($jsonobj->d->results as $value){
            array_push($arr, $value->Thumbnail->MediaUrl);
        }
    }
    elseif($type=='Web'){
        foreach($jsonobj->d->results as $value){
            array_push($arr, array('title'=>$value->Title,'description'=>$value->Description,'url'=>$value->Url));
        }
    }
    return $arr;
}

function wiki($srsearch,$lng){
    if (ctype_alnum($srsearch)) {
        $lng='en';
    }
    else{
        $lng='zh';
        $srsearch=urlencode($srsearch);
    }
    $wiki_api_url='http://'.$lng.'.wikipedia.org/w/api.php?action=query&list=search&srwhat=text&format=xml&srsearch='.$srsearch;
    $result=file_get_contents($wiki_api_url);
    $xmldata=simplexml_load_string($result);

    $arr=array();
    foreach ($xmldata->query->search->p as $value) {
        $value['snippet']=str_replace(array('<span class=\'searchmatch\'>','</span>','<b>','</b>',' '), '', $value['snippet']);
        array_push($arr,array('title'=>$value['title'],'snippet'=>$value['snippet']));
    }
    return $arr;
}

function jokes(){
    $jokes_api_url='http://www.djdkx.com/open/randxml';
    $result=file_get_contents($jokes_api_url);
    $xmldata=simplexml_load_string($result,'SimpleXMLElement',LIBXML_NOCDATA);
    $xmldata->content=str_replace(array('&nbsp;','<br/>'), '', trim($xmldata->content));
    return $xmldata->content;
}

function baiduNews(){
    $word='最新';
    $num=5;
    $baidunews_api_url='http://news.baidu.com/ns?tn=newsfcu&from=news&cl=2&ct=0&rn='.$num.'&word='.$word;
    $result=file_get_contents($baidunews_api_url);
    $reg='#<a[\s]+href="(?<url>[^\s>]+)"[^>]*target="_blank">(?<title>[^>]+)</a>&nbsp;<span>(?<resrc>[^>]+)</span>#i';
    preg_match_all($reg, $result, $matches);
    $matches[resrc]=str_replace('&nbsp;',' ',$matches[resrc]);
    $data=array(array('title'=>'关心国家大事，了解天下奇谈，小u给你播报新闻啦！'));
    for ($i=0; $i < 5; $i++) { 
        $matches[title][$i]=iconv('gbk','utf-8',$matches[title][$i]);
        $matches[resrc][$i]=iconv('gbk','utf-8',$matches[resrc][$i]);
        array_push($data,array('title'=>$matches[title][$i]."\n".'------------------------------------------','note'=>$matches[resrc][$i],'link'=>$matches[url][$i]));
    }
    return $data;
}

function doubanMovies($word){
    $list=array('top10'=>'top250','北美票房榜'=>'us_box');
    $doubanMovie_api_url='http://api.douban.com/v2/movie/';
    if (!isset($list[$word])) {
        $result=file_get_contents($doubanMovie_api_url.'search?q='.$word);
    }
    else{
        $result=file_get_contents($doubanMovie_api_url.$list[$word]);
    }
    $jsondata=json_decode($result);
    $movie=array();
    foreach ($jsondata->subjects as $value) {
        if ($list[$word]=='us_box') {
            $value=$value->subject;
        }
        array_push($movie, array('average'=>$value->rating->average,'title'=>$value->title,'original_title'=>$value->original_title,'year'=>$value->year,'images'=>$value->images,'alt'=>$value->alt,'id'=>$value->id));
    }
    return $movie;
}

function doubanMovie($id){
    $doubanMovie_api_url='http://api.douban.com/v2/movie/subject/';
    $result=file_get_contents($doubanMovie_api_url.$id);
    $jsondata=json_decode($result);
    $movie=array('average'=>$jsondata->rating->average,'year'=>$jsondata->year,'images'=>$jsondata->images->large,'mobile_url'=>$jsondata->mobile_url,'title'=>$jsondata->title,'genres'=>$jsondata->genres,'countries'=>$jsondata->countries,'summary'=>$jsondata->summary,'aka'=>$jsondata->aka,'directors'=>$jsondata->directors,'casts'=>$jsondata->casts);
    return $movie;
}

function doubanBooks($word){
    $doubanBook_api_url='https://api.douban.com/v2/book/search?count=5&q=';
    $result=file_get_contents($doubanBook_api_url.$word);
    $jsondata=json_decode($result);
    $book=array();
    foreach ($jsondata->books as $value) {
        array_push($book, array('average'=>$value->rating->average,'author'=>$value->author,'pubdate'=>$value->pubdate,'images'=>$value->images,'alt'=>$value->alt,'title'=>$value->title,'publisher'=>$value->publisher,'summary'=>$value->summary,'price'=>$value->price,'tags'=>$value->tags,'id'=>$value->id));
    }
    return $book;
}

function doubanBook($id){
    $doubanBook_api_url='https://api.douban.com/v2/book/';
    $result=file_get_contents($doubanBook_api_url.$id);
    $jsondata=json_decode($result);
    $book=array('average'=>$jsondata->rating->average,'author'=>$jsondata->author,'pubdate'=>$jsondata->pubdate,'images'=>$jsondata->images->large,'alt'=>$jsondata->alt,'title'=>$jsondata->title,'publisher'=>$jsondata->publisher,'summary'=>$jsondata->summary,'price'=>$jsondata->price,'tags'=>$jsondata->tags);
    return $book;
}

function doubanMusics($word){
    $doubanMusic_api_url='https://api.douban.com/v2/music/search?count=5&q=';
    $result=file_get_contents($doubanMusic_api_url.$word);
    $jsondata=json_decode($result);
    $music=array();
    foreach ($jsondata->musics as $value) {
        array_push($music, array('average'=>$value->rating->average,'author'=>$value->author,'pubdate'=>$value->attrs->pubdate,'image'=>$value->image,'alt'=>$value->alt,'title'=>$value->title,'publisher'=>$value->attrs->publisher,'singer'=>$value->attrs->singer,'version'=>$value->attrs->version,'id'=>$value->id));
    }
    return $music;
}

function doubanMusic($id){
    $doubanMusic_api_url='https://api.douban.com/v2/music/';
    $result=file_get_contents($doubanMusic_api_url.$id);
    $jsondata=json_decode($result);
    $music=array('average'=>$jsondata->rating->average,'author'=>$jsondata->author,'summary'=>$jsondata->summary,'image'=>$jsondata->image,'mobile_link'=>$jsondata->mobile_link,'title'=>$jsondata->title,'id'=>$jsondata->id,'publisher'=>$jsondata->attrs->publisher,'singer'=>$jsondata->attrs->singer,'version'=>$jsondata->attrs->version,'pubdate'=>$jsondata->attrs->pubdate,'tags'=>$jsondata->tags);
    return $music;
}

function dream($word){
    $dream_api_url='http://api2.sinaapp.com/search/dream/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.urlencode($word);
    $result=file_get_contents($dream_api_url);
    $dream=json_decode($result);
    return $dream->text->content;
}

function phoneNumber($num){
    $phoneNum_api_url='http://cz.115.com/?ct=index&ac=get_mobile_local&mobile='.$num;
    $result=str_replace(array('(',')'), '', file_get_contents($phoneNum_api_url));
    $phone=json_decode($result);
    $phoneNum=array('province'=>$phone->province,'city'=>$phone->city,'corp'=>$phone->corp);
    return $phoneNum;
}

function lotterySearch($word){
    $lottery_api_url='http://api2.sinaapp.com/search/lottery/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.urlencode($word);
    $result=file_get_contents($lottery_api_url);
    $lottery=json_decode($result);
    return $lottery->text->content;
}
function lottery(){
    $lotteryArray=array('双色球','七乐彩','大乐透','七星彩','排列3','排列5','胜负彩','六场半全场');
    foreach ($lotteryArray as $value) {
        $lottery.=lotterySearch($value)."\n";
    }
    return $lottery;
}

function picRec($url){
    $picRec_api_url='http://api2.sinaapp.com/recognize/picture/?appkey=2989441965&appsecert=4e5e32eb22617e691165e16d6a152a18&reqtype=text&keyword='.$url;
    $result=file_get_contents($picRec_api_url);
    $pic=json_decode($result);
    return $pic->text->content;
}

function python($cmd){
    $url='http://urinx.sinaapp.com/'.$cmd;
    $result=file_get_contents($url);
    return $result;
}

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
    $data=array(array('title'=>'Stack Overflow','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
    $raw=array();
    
    foreach ($arr as $reg){
        preg_match_all($reg, $result, $matches);
        array_push($res,$matches[1]);
    }
    
    for ($i=0; $i < 5; $i++) {
        list($id,$title)=preg_split('#/#',$res[0][$i]);
        array_push($raw,array('id'=>$id,'title'=>$title,'vote'=>$res[1][$i],'answer'=>$res[2][$i],'view'=>$res[3][$i]));
    }

    for ($i=0; $i < 5; $i++) {
        array_push($data, array('title'=>str_replace('-',' ',$raw[$i]['title']),'note'=>'votes:'.$raw[$i]['vote'].' answers:'.$raw[$i]['answer'].' views:'.$raw[$i]['view'],'link'=>'http://stackoverflow.com/questions/'.$raw[$i]['id']));
    }
    return $data;
}

function bilibili(){
    $data=array(array('title'=>'Bilibili','cover'=>$web.'/img/bili/'.mt_rand(0,10).'.jpg'));
    $res=array();
    $category=array('1'=>'动画','3'=>'音乐','4'=>'游戏','5'=>'娱乐','11'=>'专辑','13'=>'新番连载');
    foreach ($category as $key=>$value){
        $url='http://www.bilibili.tv/rss-'.$key.'.xml';
        $result=file_get_contents($url);
        $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);
        array_push($res,array('title'=>$xml->channel->item->title,'description'=>$xml->channel->item->description,'category'=>$value,'link'=>$xml->channel->item->link));
    }
    for ($i=0; $i < count($res); $i++) {
        array_push($data, array('title'=>$res[$i]['category'].':'.$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
    }
    return $data;
}

function rss($keyws){
    $url_arr=array('0x50sec'=>'http://www.0x50sec.org/feed/','乌云'=>'http://www.wooyun.org/feeds/submit','91ri'=>'http://www.91ri.org/feed','设计达人'=>'http://www.shejidaren.com/feed','运维'=>'http://www.nginx.cn/feed','freebuf'=>'http://www.freebuf.com/feed','matrix67'=>'http://www.matrix67.com/blog/feed','读书'=>'http://rss.sina.com.cn/book/history_rank.xml','松鼠'=>'http://songshuhui.net/feed','爱范'=>'http://www.ifanr.com/feed','人人影视'=>'http://www.yyets.com/rss/feed/');
    $reg=array('乌云'=>'#<strong>简要描述：</strong><br/>(.+)<br/><strong>详细说明#i','设计达人'=>'#<img src="(http://images\.shejidaren\.com/wp-content/uploads/.+)"[\s]+class#i','爱范'=>'#(.+)题图来自#i','人人影视'=>'#<img[\s]+src="(http://res\.yyets\.com/ftp/.+\.jpg)"[\s]+/>#i',''=>'',);
    $data=array();
    $result=file_get_contents($url_arr[$keyws]);
    $xml=simplexml_load_string($result,'SimpleXMLElement', LIBXML_NOCDATA);

    for ($i=0; $i < 6; $i++) {
        $n='';
        $c='';
        if (isset($reg[$keyws])) {
            preg_match_all($reg[$keyws],$xml->channel->item[$i]->description, $matches);
            switch ($keyws) {
                case '乌云':
                    $n='简要描述：'.$matches[1][0];
                    break;

                case '设计达人':
                    $c=$matches[1][0];
                    break;

                case '爱范':
                    $n=$matches[1];
                    $c=$xml->channel->item[$i]->image;
                    break;

                case '人人影视':
                    $n=$xml->channel->item[$i]->description;
                    preg_match_all($reg[$keyws], file_get_contents($xml->channel->item[$i]->link), $matches);
                    $c=$matches[1][0];
                    break;
                
                default:
                    # code...
                    break;
            }
        }
        else{
            $n=htmlspecialchars_decode(str_replace('&#160;','',$xml->channel->item[$i]->description));
        }
        array_push($data,array('title'=>$xml->channel->item[$i]->title,'note'=>$n,'link'=>$xml->channel->item[$i]->link,'cover'=>$c));
    }

    return $data;
}
?>