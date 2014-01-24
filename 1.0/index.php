<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');

$appdir=dirname(__FILE__);
require $appdir . '/wechat-api.php';

mysql_connect('localhost','paiplace_Uri','lovotolqy');
mysql_select_db('paiplace_wechat');
mysql_query("SET NAMES 'UTF8'");

if(W::isPOST()){
    $post=$GLOBALS["HTTP_RAW_POST_DATA"];
    $xml=simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);
    $content=strtolower(trim($xml->Content));
    $type=strtolower($xml->MsgType);
    $openid=$xml->FromUserName;
    $web='http://paiplace.5gbfree.com/wechat';
    $mp='http://mp.weixin.qq.com/mp/appmsg/show';
    $xiaoU=array(
        array('title'=>'Uri','cover'=>$web.'/img/xiaou.jpg','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&appmsgid=10000058&itemidx=1&sign=93bf84e650d4df87196988b342b65644&uin=MTg0MTcyODUwMQ%3D%3D&key=1f75b224f2ddfcb63ee0ec9833578cef7b1a67f0a5b8764f174219e1dea6ff39600f23b296cd7601a031fb617c173721&devicetype=android-17&version=25000104&lang=zh_CN'),
        array('title'=>'功能介绍','cover'=>$web.'/img/function.png','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&appmsgid=10000058&itemidx=2&sign=aa817cfb12b9117cb518d4ab268d28b9&uin=MTg0MTcyODUwMQ%3D%3D&key=a45a7c15a542fe6fc8cb6988104ee36150747c2ada475afb2ac31bce5694e6c3926bef8e4a56de8e63d7ca0e91c68a1f&devicetype=android-17&version=25000104&lang=zh_CN'),
        array('title'=>'任务进度','cover'=>$web.'/img/taskprocess.png','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&appmsgid=10000058&itemidx=3&sign=5edb8953ca274271a239626c434df4a2&uin=MTg0MTcyODUwMQ%3D%3D&key=1f75b224f2ddfcb6c670a05130da7f8843902f3277a89843ae023cec9a770260f883566234a1185fec8257197f241425&devicetype=android-17&version=25000104&lang=zh_CN'),
        array('title'=>'联系我们','cover'=>$web.'/img/contactme.png','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&appmsgid=10000058&itemidx=4&sign=a7d37be0a672e5c2085330799836992b&uin=MTg0MTcyODUwMQ%3D%3D&key=1f75b224f2ddfcb62c58bc42a307d4f512acd5bfc822af05b55a2f55325983e93e612b5c098985be9b9909a57e7eab5e&devicetype=android-17&version=25000104&lang=zh_CN')
    );
    $biaoqing=array(
        '/::)','/::~','/::B','/::|','/:8-)','/::<','/::$','/::X','/::Z','/::\'(','/::-|',
        '/::@','/::P','/::D','/::O','/::(','/::+','/:--b','/::Q','/::T','/:,@P','/:,@-D',
        '/::d','/:,@o','/::g','/:|-)','/::!','/::L','/::>','/::,@','/:,@f','/::-S','/:?',
        '/:,@x','/:,@@','/::8','/:,@!','/:!!!','/:xx','/:bye','/:wipe','/:dig','/:handclap',
        '/:&-(','/:B-)','/:<@','/:@>','/::-O','/:>-|','/:P-(','/::\'|','/:X-)','/::*','/:@x',
        '/:8*');

    // 接收位置
    if($type=='location'){
        $uriID='oTHwbt_9H0QVjJn8AdI-fmkHdKgM';
        $X=$xml->Location_X;
        $Y=$xml->Location_Y;

        $res=mysql_query("SELECT * FROM wechat WHERE FromUserName='{$uriID}'");
        $row=mysql_fetch_row($res);
        $pre_X=$row[4];
        $pre_Y=$row[5];

        $wData=weatherAPI($X,$Y);//天气查询
        $distance=W::getDistance($X,$Y,$pre_X,$pre_Y);//计算距离
        
        if ($openid == $uriID) {
            mysql_query("UPDATE wechat SET Location_X='{$X}',Location_Y='{$Y}' WHERE FromUserName='{$uriID}'");
            $data='主人的位置已上传'."\n".'X:'.$X."\n".'Y:'.$Y."\n".'相距上次位置'.$distance.'km';
        }
        else{
            $data='哦哦，你和我的距离只有'.$distance.'km哟';
        }
        $data.="\n".'------------------------------'."\n".$wData;
    }

    //接收文字
    elseif($type=='text'){
        if(ctype_alpha($content)) {
            $data=translateAPI($content);
        }
        //phoneNumber
        elseif(ctype_alnum($content)){
            $phoneNum=phoneNumber($num);
            $data=$phoneNum['city']."\n".$phoneNum['corp'];
        }
        elseif($content=='小u'){
            $data=$xiaoU;
        }
        elseif($content=='帮助'){
            $data='这是目前小u有的功能:'."\n".'-----------------'."\n".'[小u]'."\n".':查看小u的基本信息，以及功能介绍和近期更新'."\n".'-----------------'."\n".'[帮助]'."\n".':查看使用帮助'."\n".'-----------------'."\n".'[查水表]'."\n".':查询寝室的水电费(华科)'."\n".'-----------------'."\n".'[每日一句]'."\n".':每天更新一句英语，中英对照'."\n".'-----------------'."\n".'[点歌]'."\n".':小u每天会为大家推荐好的歌曲，希望大家喜欢。如果想给某人点歌的话，可以直接跟我说哦'."\n".'-----------------'."\n".'[笑话]'."\n".':郁闷时看看笑话吧，小u这里有好多笑话等着你呢'."\n".'-----------------'."\n".'[新闻]'."\n".':没事的时候大家多看看新闻吧，小u不懈的为你奉送中'."\n".'-----------------'."\n".'[彩票]'."\n".':每天的彩票信息一目了然'."\n".'-----------------'."\n".'翻译'."\n".':发送"#+你要翻译的内容",即可收到详细结果，例如:#doofus'."\n".'-----------------'."\n".'天气+找小u'."\n".':点击下面的“+”，发送你的的位置信息，即可收到本地的天气预报，并且看到你和小u的距离哟。'."\n".'-----------------'."\n".'bing搜索'."\n".':发送"%+你要搜索的内容",即可收到详细结果，例如:%dweeb'."\n".'-----------------'."\n".'维基百科'."\n".':发送"&+你要搜索的内容",小u会根据你的输入自动判断查询中文维基或是英文,(*^__^*) 嘻嘻。例如:&spaz'."\n".'-----------------'."\n".'二维码'."\n".':发送"*+你要生成的内容",小u会返回生成的二维码。例如:*嘟嘟噜'."\n".'-----------------';
            $data.="\n".'豆瓣'."\n".'1.书'."\n".' bs:关键字 搜索相关的书籍'."\n".' b:书名 查看详细内容'."\n".'2.音乐'."\n".' ms:关键字 搜索相关的音乐'."\n".' m:音乐名 查看详细内容'."\n".'3.电影'."\n".' vs:关键字 搜索相关的电影'."\n".' v:电影名 查看详细内容'."\n".'-----------------';
            $data.="\n".'动态'."\n".':发送":+你要分享的文字"即可，大家可以回复[动态]查看，都可以看到哦。例如 :这是我发的第一个说说'."\n".'-----------------';
            $data.="\n".'美女识别'."\n".':上传图片，看看小u的眼力吧'."\n".'-----------------'."\n".'周公解梦'."\n".':发送"梦到xxx",小u来预测吉凶，例如"梦到小u"'."\n".'-----------------'."\n".'手机号码查询'."\n".':直接发送手机号'."\n".'-----------------';
            $data.="\n".'#[xx]内的内容xx是指你发送给小u的';
        }
        elseif($content=='查水表'){
            $data='查询电费请点击这里：'."\n".'<a href="http://42.120.22.130/dianfei.php">查电费</a>。'."\n".'低余电费自动提醒功能请点击这里：'."\n".'<a href="http://42.120.22.130:8822/">邮件提醒</a>';
        }
        elseif($content=='点歌'){
            $data =array('title'=>'你给的甜', 'description'=>'何艺纱', 'musicurl'=>'http://data7.5sing.com/T1aMbeBXbT1R47IVrK.mp3', 'HQmusicurl'=>'http://data7.5sing.com/T1aMbeBXbT1R47IVrK.mp3');
        }
        elseif($content=='每日一句'){
            $data=en_sentenceAPI();
        }
        elseif($content=='笑话'){
            $data=jokes();
        }
        elseif($content=='彩票'){
            $data=lottery();
        }
        elseif($content=='新闻'){
            $news=baiduNews();
            $data=array();
            for ($i=0;$i<5;$i++){ 
                array_push($data,array('title'=>$news[title][$i]."\n".'------------------------------------------','note'=>$news[resrc][$i],'link'=>$news[url][$i]));
            }
        }
        //dream
        elseif(preg_match('/^(梦到)(.+)/i', $content, $matches)){
            $data=dream($matches[2]);
        }
        //translate
        elseif(preg_match('/^(#)(.+)/i', $content, $matches)){
            $data=translateAPI($matches[2]);
        }
        //bing web search
        elseif(preg_match('/^(%)(.+)/i',$content,$matches)){
            $bingweb=bing($matches[2],'Web');
            $bingimage=bing($matches[2],'Image');
            $data=array(
                array('title'=>$matches[2],'note'=>'','cover'=>$bingimage[0],'link'=>'')
            );
            //max:7
            for ($i=0;$i<5;$i++){ 
                array_push($data,array('title'=>$bingweb[$i]['title']."\n".'------------------------------------------','note'=>$bingweb[$i]['description'],'cover'=>'','link'=>$bingweb[$i]['url']));
            }
        }
        //wiki
        elseif (preg_match('/^(&)(.+)/i',$content,$matches)){
            $wiki=wiki($matches[2],$lng);
            $data=array();
            for ($i=0;$i<5;$i++){ 
                array_push($data,array('title'=>$wiki[$i]['title']."\n".'------------------------------------------','note'=>$wiki[$i]['snippet'],'cover'=>'','link'=>'http://zh.wikipedia.org/wiki/'.$wiki[$i]['title']));
            }
        }
        //qrcode
        elseif(preg_match('/^(\*)(.+)/i',$content,$matches)){
            $qrcode='http://chart.apis.google.com/chart?cht=qr&chs=400x400&choe=UTF-8&chl='.$matches[2];
            $data=array(
                array('title'=>$matches[2],'cover'=>$qrcode,'link'=>$qrcode)
            );
        }
        //doubanMovie
        elseif(preg_match('/^(vs:)(.+)/i',$content,$matches)){
            $m=doubanMovies($matches[2]);
            $data=array(array('title'=>$m[0]['title'],'note'=>$m[0]['year'].' '.$m[0]['average'],'cover'=>$m[0]['images']->large,'link'=>$m[0]['alt']));
            for ($i=1;$i<=5;$i++){ 
                array_push($data,array('title'=>$m[$i]['title'],'note'=>'又名:'.$m[$i]['original_title']."\n".'上映日期:'.$m[$i]['year']."\n".'评价:'.$m[$i]['average'],'cover'=>$m[$i]['images']->small,'link'=>$m[$i]['alt']));
            }
        }
        elseif(preg_match('/^(v:)(.+)/i',$content,$matches)){
            $m=doubanMovies($matches[2]);
            $movie=doubanMovie($m[0]['id']);
            $xinxi='又名:';
            foreach ($movie['aka'] as $value) {
                $xinxi.=$value.'/';
            }
            $xinxi.="\n".'上映日期:'.$movie['year']."\n".'制片国家:';
            foreach ($movie['countries'] as $value) {
                $xinxi.=$value.'/';
            }
            $xinxi.="\n".'类型:';
            foreach ($movie['genres'] as $value) {
                $xinxi.=$value.'/';
            }
            $xinxi.="\n".'评价:'.$movie['average'];
            $data=array(
                array('title'=>$movie['title'],'cover'=>$movie['images'],'link'=>$movie['mobile_url']),
                array('note'=>$xinxi,'link'=>$movie['mobile_url']),
                array('title'=>'简介','note'=>$movie['summary'],'link'=>$movie['mobile_url']),
                array('title'=>'导演','note'=>$movie['directors'][0]->name,'cover'=>$movie['directors'][0]->avatars->small,'link'=>$movie['directors'][0]->alt),
                array('title'=>'主演','note'=>$movie['casts'][0]->name,'cover'=>$movie['casts'][0]->avatars->small,'link'=>$movie['casts'][0]->alt)
            );
        }
        //doubanBook
        elseif(preg_match('/^(bs:)(.+)/i',$content,$matches)){
            $b=doubanBooks($matches[2]);
            $data=array(array('title'=>$b[0]['title'],'note'=>$b[0]['author'][0].' '.$b[0]['average'],'cover'=>$b[0]['images']->large,'link'=>$b[0]['alt']));
            for ($i=1;$i<=4;$i++){ 
                array_push($data,array('title'=>$b[$i]['title'],'note'=>'作者:'.$b[$i]['author'][0]."\n".'出版社:'.$b[$i]['publisher']."\n".'出版日期:'.$b[$i]['pubdate']."\n".'价格:'.$b[$i]['price']."\n".'评价:'.$b[$i]['average']."\n".'导言:'.$b[$i]['summary'],'cover'=>$b[$i]['images']->small,'link'=>$b[$i]['alt']));
            }
        }
        elseif(preg_match('/^(b:)(.+)/i',$content,$matches)){
            $b=doubanBooks($matches[2]);
            $bk=doubanBook($b[0]['id']);
            $data=array(
                array('title'=>$bk['title'],'cover'=>$bk['images'],'link'=>$bk['alt']),
                array('note'=>'作者:'.$bk['author'][0]."\n".'出版社:'.$bk['publisher']."\n".'出版日期:'.$bk['pubdate']."\n".'价格:'.$bk['price']."\n".'评价:'.$bk['average'],'link'=>$bk['alt']),
                array('title'=>'导言:','note'=>$bk['summary'],'link'=>$bk['alt'])
            );
        }
        //doubanMusic
        elseif(preg_match('/^(ms:)(.+)/i',$content,$matches)){
            $m=doubanMusics($matches[2]);
            $data=array(array('title'=>$m[0]['title'],'note'=>$m[0]['average'],'cover'=>$m[0]['image'],'link'=>$m[0]['alt']));
            for ($i=1;$i<=4;$i++){ 
                array_push($data,array('title'=>$m[$i]['title'],'note'=>'作者:'.$m[$i]['author'][0]->name."\n".'出版社:'.$m[$i]['publisher'][0]."\n".'出版日期:'.$m[$i]['pubdate'][0]."\n".'表演者:'.$m[$i]['singer'][0]."\n".'评价:'.$m[$i]['average']."\n".'类型:'.$m[$i]['version'][0],'cover'=>$m[$i]['image'],'link'=>$m[$i]['alt']));
            }
        }
        elseif(preg_match('/^(m:)(.+)/i',$content,$matches)){
            $m=doubanMusics($matches[2]);
            $mu=doubanMusic($m[0]['id']);
            $data=array(
                array('title'=>$mu['title'],'cover'=>$mu['image'],'link'=>$mu['mobile_link']),
                array('note'=>'表演者:'.$mu['singer'][0]."\n".'专辑类型:'.$mu['version'][0]."\n".'发行时间:'.$mu['pubdate'][0]."\n".'作者:'.$mu['author'][0]->name."\n".'出版者:'.$mu['publisher'][0]."\n".'评价:'.$mu['average'],'link'=>$mu['mobile_link']),
                array('title'=>'简介:','note'=>$mu['summary'],'link'=>$mu['mobile_link'])
            );
        }
        //moments
        elseif(preg_match('/^(:)(.+)/i',$content,$matches)){
            $moment=$matches[2];
            $time=time();
            $date=date("Y-m-d H:i:s",time());

            $res=mysql_query("SELECT * FROM wechat WHERE FromUserName='{$openid}'");
            $row=mysql_fetch_array($res);
            $alias=$row['alias'];
            if (!$alias) {
                $data='由于你是第一次发送动态，请按照以下格式回复基本信息:'."\n".'昵称:+你的昵称';
            }
            else{
                mysql_query("INSERT INTO moments(FromUserName,alias,moment,time,date) VALUES ('{$openid}','{$alias}','{$moment}',{$time},'{$date}')");
                $data="发送成功，你可以现在上传一张图片作为配图，当然也可以不上传啦。回复[动态]查看";
            }
        }
        elseif($content=='动态'){
            $res=mysql_query("SELECT * FROM moments ORDER BY ID DESC");
            $data=array(array('title'=>'动态','cover'=>$web.'/img/meizi/2.jpg'));
            for ($i=0; $i < 5; $i++) {
                if ($row=mysql_fetch_array($res)) {
                    array_push($data, array('title'=>$row['alias'].':','note'=>$row['moment']."\n".$row['date'],'cover'=>$row['photo']));
                }
            }
        }
        elseif(preg_match('/^(昵称:)(.+)/i',$content,$matches)){
            $alias=$matches[2];
            $res=mysql_query("SELECT * FROM wechat");
            while ($row=mysql_fetch_array($res)) {
                if ($alias==$row['alias'] || $alias=='小u' || $alias=='urinx') {
                    $t=1;
                }
                elseif ($openid==$row['FromUserName']) {
                    $u=1;
                }
            }
            if ($t) {
                $data='该用户名已被注册，请重新输入';
            }
            else{
                if ($u) {
                    mysql_query("UPDATE wechat SET alias='{$alias}' WHERE FromUserName='{$openid}'");
                    $data='更改成功!';
                }
                else{
                    mysql_query("INSERT INTO wechat(FromUserName,alias) VALUES ('{$openid}','{$alias}')");
                    $data='注册成功!';
                }
            }
        }
        //biaoqing
        elseif (preg_match('#(/:)#i',$content)) {
            $data=$biaoqing[rand(0,count($biaoqing)-1)];
        }
    }

    //接收图片
    elseif($type=='image'){
        $picurl=$xml->PicUrl;
        mysql_query("UPDATE moments SET photo='{$picurl}' WHERE FromUserName='{$openid}'");
        $data='你的图片已上传成功!';
        $data.="\n".picRec($picurl);
    }

    //接收事件
    elseif($type=='event'){
        $event=strtolower($xml->Event);
        if($event=='subscribe'){
            $data=$xiaoU;
        }
    }

    //贱鸡自动回复
    if ($data) {
        if (is_array($data)) {
            if ($data['musicurl']) {
               exit(W::response($xml,$data,'music'));
            }
            exit(W::response($xml,$data,'news'));
        }
        else{exit(W::response($xml,$data));}
    }
    else{
        if (rand(1,8)==6){
            switch (rand(1,4)) {
                case 1:
                    exit(W::response($xml,"聊了这么久小u给你讲个笑话吧：\n".jokes()));
                    break;

                case 2:
                    $news=baiduNews();
                    $data=array(array('title'=>'关心国家大事，了解天下奇谈，小u给你播报新闻啦！'));
                    for ($i=0;$i<5;$i++){ 
                        array_push($data,array('title'=>$news[title][$i]."\n".'------------------------------------------','note'=>$news[resrc][$i],'link'=>$news[url][$i]));
                    }
                    exit(W::response($xml,$data,'news'));
                    break;

                case 3:
                    exit(W::response($xml,"四六级没考过吧孩子，还是乖乖跟我学英语，嗯哼：\n".en_sentenceAPI()."\n哎哟，你还可以发英语单词考我哦，没有我不知道的，嘻嘻/:B-)"));
                    break;

                case 4:
                    exit(W::response($xml,"妹子爆个照吧，小u想看看嘛，看了会说话嘛/:8*"));
                    break;
                
                default:
                    break;
            }
        }
        else{
            exit(W::response($xml,simsimi($content)));
        }
    }
}

?>