<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');

$appdir=dirname(__FILE__);
require $appdir . '/wechat-api.php';

$mysql=new SaeMysql();
$s = new SaeStorage();
$f = new SaeFetchurl();

if(W::isPOST()){
    $post=$GLOBALS["HTTP_RAW_POST_DATA"];
    $xml=simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);
    $content=trim($xml->Content);
    $type=strtolower($xml->MsgType);
    $openid=$xml->FromUserName;
    $web='http://mmbiz.qpic.cn/mmbiz/';
    $mp='http://mp.weixin.qq.com/s';
    $xiaoU=array(
        array('title'=>'Uri','cover'=>$web.'icv3Y2jicj1r5zVuHbibOR7ztYtd6tl8zShn0ibKxLCavToicWMuQ8iaJ9wiaK5AqB19KdDOvGXvPQibKfnFABtWIVpwtQ/0?wxfrom=5','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&mid=10000058&idx=1&sn=93bf84e650d4df87196988b342b65644#rd'),
        array('title'=>'功能介绍','cover'=>$web.'icv3Y2jicj1r5zVuHbibOR7ztYtd6tl8zShXae61cDyL3FDfgB2CEsXyI4lqDXyUyqwGIvWjjKm0jlOy3fCfhPibuQ/0?wxfrom=5','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&mid=10000058&idx=2&sn=aa817cfb12b9117cb518d4ab268d28b9#rd'),
        array('title'=>'任务进度','cover'=>$web.'icv3Y2jicj1r5zVuHbibOR7ztYtd6tl8zShbfNUxPaicSwXY3daHy1BVbxpx7CEjym1VicU4b3T6dSd6XIjiaRE0lRkA/0?wxfrom=5','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&mid=10000058&idx=3&sn=5edb8953ca274271a239626c434df4a2#rd'),
        array('title'=>'联系我们','cover'=>$web.'icv3Y2jicj1r5zVuHbibOR7ztYtd6tl8zShO7B8lIEN05Niao9CnJGYsFFviayfqRyOrPGj5JwfGa8zmDYEf1S2DVaA/0?wxfrom=5','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&mid=10000058&idx=4&sn=a7d37be0a672e5c2085330799836992b#rd')
    );
    $biaoqing=array(
        '/::)','/::~','/::B','/::|','/:8-)','/::<','/::$','/::X','/::Z','/::\'(','/::-|',
        '/::@','/::P','/::D','/::O','/::(','/::+','/:--b','/::Q','/::T','/:,@P','/:,@-D',
        '/::d','/:,@o','/::g','/:|-)','/::!','/::L','/::>','/::,@','/:,@f','/::-S','/:?',
        '/:,@x','/:,@@','/::8','/:,@!','/:!!!','/:xx','/:bye','/:wipe','/:dig','/:handclap',
        '/:&-(','/:B-)','/:<@','/:@>','/::-O','/:>-|','/:P-(','/::\'|','/:X-)','/::*','/:@x',
        '/:8*');

    $ghost=array('短篇鬼故事'=>1,'长篇鬼故事'=>2,'校园鬼故事'=>3,'医院鬼故事'=>4,'家里鬼故事'=>5,
    '民间鬼故事'=>6,'灵异事件'=>7,'听鬼故事'=>10,'中国灵异'=>25,'灵异知识库'=>26,'推理小说'=>46);
    
    $help='这是目前小u有的功能:'."\n".'-----------------'."\n".'[小u]'."\n".':查看小u的基本信息，以及功能介绍和近期更新'."\n".'-----------------'."\n".'[帮助]'."\n".':查看使用帮助'."\n".'-----------------'."\n".'[查水表]'."\n".':查询寝室的水电费(华科)'."\n".'-----------------'."\n".'[每日一句]'."\n".':每天更新一句英语，中英对照'."\n".'-----------------'."\n".'[点歌]'."\n".':小u每天会为大家推荐好的歌曲，希望大家喜欢。如果想给某人点歌的话，可以直接跟我说哦'."\n".'-----------------'."\n".'[笑话]'."\n".':郁闷时看看笑话吧，小u这里有好多笑话等着你呢'."\n".'-----------------'."\n".'[新闻]'."\n".':没事的时候大家多看看新闻吧，小u不懈的为你奉送中'."\n".'-----------------'."\n".'[彩票]'."\n".':每天的彩票信息一目了然'."\n".'-----------------'."\n".'翻译'."\n".':发送"#+你要翻译的内容",即可收到详细结果，例如:#doofus'."\n".'-----------------'."\n".'天气+找小u'."\n".':点击下面的“+”，发送你的的位置信息，即可收到本地的天气预报，并且看到你和小u的距离哟。'."\n".'-----------------'."\n".'bing搜索'."\n".':发送"%+你要搜索的内容",即可收到详细结果，例如:%dweeb'."\n".'-----------------'."\n".'维基百科'."\n".':发送"&+你要搜索的内容",小u会根据你的输入自动判断查询中文维基或是英文,(*^__^*) 嘻嘻。例如:&spaz'."\n".'-----------------'."\n".'二维码'."\n".':发送"*+你要生成的内容",小u会返回生成的二维码。例如:*嘟嘟噜'."\n".'-----------------';
    $help.="\n".'豆瓣'."\n".'1.书'."\n".' bs:关键字 搜索相关的书籍'."\n".' b:书名 查看详细内容'."\n".'2.音乐'."\n".' ms:关键字 搜索相关的音乐'."\n".' m:音乐名 查看详细内容'."\n".'3.电影'."\n".' vs:关键字 搜索相关的电影'."\n".' v:电影名 查看详细内容'."\n".'-----------------';
    $help.="\n".'动态'."\n".':发送":+你要分享的文字"即可，大家可以回复[动态]查看，都可以看到哦。例如 :这是我发的第一个说说'."\n".'-----------------';
    $help.="\n".'美女识别'."\n".':上传图片，看看小u的眼力吧'."\n".'-----------------'."\n".'周公解梦'."\n".':发送"梦到xxx",小u来预测吉凶，例如"梦到小u"'."\n".'-----------------'."\n".'手机号码查询'."\n".':直接发送手机号'."\n".'-----------------';
    $help.="\n".'[鬼故事]'."\n".':小u精心为你准备的鬼故事哟，超吓人的说，午夜凌晨看一看好阔怕。。'."\n".'-----------------'."\n".'[短篇鬼故事]'."\n".':让喜欢鬼故事的读者在这里快速感受到不一样的诡异，篇幅较短，适合想快速阅读完鬼故事的朋友'."\n".'-----------------'."\n".'[长篇鬼故事]'."\n".':篇幅较长，情节曲折，剧情跌宕起伏，一个好的长篇鬼故事，就是一次刺激的心灵旅行'."\n".'-----------------'."\n".'[校园鬼故事]'."\n".':这里的鬼故事都与校园有关或者是发生在学校里的鬼故事，让喜爱鬼故事的读者了解更多的校园鬼故事'."\n".'-----------------'."\n".'[医院鬼故事]'."\n".':我们在恐怖鬼片里也常见到发生在医院里的鬼故事事件，但有更多的鬼故事等待你去发现，一起来看医院鬼故事吧'."\n".'-----------------'."\n".'[家里鬼故事]'."\n".':发生在家里的鬼故事比较多，快来看看都有哪些恐怖的家里鬼故事吧'."\n".'-----------------';
    $help.="\n".'[民间鬼故事]'."\n".':中国民间鬼故事来源于古代的传奇小说，和现代的民间流传奇闻中。同时鬼故事网也搜集了国外吸血鬼的故事，欢迎大家阅读民间鬼故事'."\n".'-----------------'."\n".'[灵异事件]'."\n".':灵异事件在世界各地每个角落经常发生，你身边是否也有灵异事件呢？灵异事件频道最全面的记录最新真实灵异事件，中国灵异事件，国外灵异事件，灵异鬼故事大全，带你进入灵异鬼故事的世界'."\n".'-----------------'."\n".'[中国灵异]'."\n".':整理最全的中国真实灵异事件大全，将会带您走入从古到今发生在中国的灵异事件'."\n".'-----------------'."\n".'[灵异知识库]'."\n".':灵异知识大全,例如如何避鬼,招鬼,阵法、鬼魂禁忌等等详细内容'."\n".'-----------------';
    $help.="\n".'[推理小说]'."\n".':内容涵盖惊悚、悬疑、侦探、武侠等，一切以推理为出发点，挑战思维极限，考验缜密的逻辑。风格：先锋、酷炫、丰富多彩、可读性强。杂志力推原创，名家新秀携手亮相，联手打造包罗万象，令人耳目一新、心驰神往的异度空间'."\n".'-----------------'."\n".'[听鬼故事]'."\n".':张震讲鬼故事,吓死你不偿命'."\n".'-----------------';
    $help.="\n".'#[xx]内的内容xx是指你发送给小u的';

    // 接收位置
    if($type=='location'){
        $uriID='oTHwbt_9H0QVjJn8AdI-fmkHdKgM';
        $X=$xml->Location_X;
        $Y=$xml->Location_Y;

        $sql="SELECT Location_X,Location_Y FROM info WHERE FromUserName='{$uriID}'";
        $sqlback=$mysql->getData($sql);
        $pre_X=$sqlback[0]['Location_X'];
        $pre_Y=$sqlback[0]['Location_Y'];

        $wData=weatherAPI($X,$Y);//天气查询
        $distance=W::getDistance($X,$Y,$pre_X,$pre_Y);//计算距离
        
        if ($openid == $uriID) {
            $mysql->runSql("UPDATE info SET Location_X='{$X}',Location_Y='{$Y}' WHERE FromUserName='{$uriID}'");
            $data='主人的位置已上传'."\n".'X:'.$X."\n".'Y:'.$Y."\n".'相距上次位置'.$distance.'km';
        }
        else{
            $data='哦哦，你和我的距离只有'.$distance.'km哟';
        }
        $data.="\n".'------------------------------'."\n".$wData;
    }

    //接收文字
    elseif($type=='text'){
        $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
        //python console
        if ($result[0]['state']==1){
            if($content=='quit'){
                $mysql->runSql("UPDATE python SET state=0 WHERE FromUserName='{$openid}'");
                $data='已退出Python终端...';
            }
            else{
                $data=python($content);
            }
        }
        //mysql console
        elseif ($result[0]['state']==2){
            if($content=='quit'){
                $mysql->runSql("UPDATE python SET state=0 WHERE FromUserName='{$openid}'");
                $data='已退出Mysql终端...';
            }
            elseif($content=='help'){
                $data='Select'."\n".'Insert'."\n".'Update'."\n".'Delete'."\n".'Create'."\n".'Drop'."\n".'Index'."\n".'Alter';
            }
            else{
                if(preg_match('#^(select)#i',$content)){
                    $sqlresult=$mysql->getData($content);
                    if($mysql->errno()!=0){
                        $data="Error:".$mysql->errmsg();
                    }
                    else{
                        $data='mysql> '.$content."\n".'-----------------'."\n";
                        foreach($sqlresult as $row){
                            foreach($row as $key=>$value){
                                $data.=$key.':'.$value."\n";
                            }
                            $data.='------------------'."\n";
                        }
                    }
                }
                else{
                    $mysql->runSql($content);
                    if($mysql->errno()!=0){
                        $data="Error:".$mysql->errmsg();
                    }
                }
            }
        }
        //sqli console
        elseif ($result[0]['state']==3){
            if($content=='quit'){
                $mysql->runSql("UPDATE python SET state=0 WHERE FromUserName='{$openid}'");
                $data='已退出Sqli终端...';
            }
            elseif($content=='login'){
                $data=array(array('title'=>'第一关','note'=>'try to login','cover'=>'http://xiaouri-wephoto.stor.sinaapp.com/QQ20140110-4.png','link'=>'http://xiaouri.sinaapp.com/test.php'));
            }
            elseif($content=='shift2'){
                $data=array(array('title'=>'第二关','note'=>'正在制作中...','cover'=>'','link'=>''));
            }
            elseif($content=='test'){
                $data=array(array('title'=>'测试','note'=>'test','link'=>'http://xiaouri.sinaapp.com/test2.php'));
            }
            else{
                $data='...';
            }
        }
        //--------------------
        else{
            $content=strtolower(trim($xml->Content));
            $keywords=array('freebuf','bilibili','stack');
            if(!in_array($content,$keywords) && ctype_alpha($content)) {
                $data=translateAPI($content);
            }
            //python
            elseif(preg_match('#^(>python)#i',$content)) {
                $data='Python 2.7.5 (default, Aug 25 2013, 00:04:04)
    [GCC 4.2.1 Compatible Apple LLVM 5.0 (clang-500.0.68)] on darwin
    Type "help", "copyright", "credits" or "license" for more information.';
                
                $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
                if (!$result[0]){
                    $mysql->runSql("INSERT INTO python(FromUserName,state) VALUES ('{$openid}',1)");
                }
                else{
                    $mysql->runSql("UPDATE python SET state=1 WHERE FromUserName='{$openid}'");
                }
            }
            //mysql
            elseif(preg_match('#^(>mysql)#i',$content)) {
                $data="Welcome to the MySQL monitor.  Commands end with ; or \g.
Server version: 5.0.45-community-nt MySQL Community Edition (GPL)
Type 'help;' or '\h' for help. Type '\c' to clear the buffer.";
                
                $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
                if (!$result[0]){
                    $mysql->runSql("INSERT INTO python(FromUserName,state) VALUES ('{$openid}',2)");
                }
                else{
                    $mysql->runSql("UPDATE python SET state=2 WHERE FromUserName='{$openid}'");
                }
            }
            //sqli
            elseif(preg_match('#^(>sqli)#i',$content)) {
                $data="sqli beta1.0\nWelcome to the sqli platform for you to leran SQL injection.We wish you enjoin this and have fun.\n\nsend 'login' to start.";
                
                $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
                if (!$result[0]){
                    $mysql->runSql("INSERT INTO python(FromUserName,state) VALUES ('{$openid}',3)");
                }
                else{
                    $mysql->runSql("UPDATE python SET state=3 WHERE FromUserName='{$openid}'");
                }
            }
            //----------------------
            elseif($content=='小u'){
                $data=$xiaoU;
            }
            elseif($content=='小幽'){
                $data='在！';
            }
            elseif($content=='帮助'){
                $data=array(array('title'=>'小u帮助指南','cover'=>$web.'icv3Y2jicj1r5zVuHbibOR7ztYtd6tl8zShn0ibKxLCavToicWMuQ8iaJ9wiaK5AqB19KdDOvGXvPQibKfnFABtWIVpwtQ/0?wxfrom=5','link'=>$mp.'?__biz=MzA3MjAzMTgyMA==&mid=10000058&idx=2&sn=aa817cfb12b9117cb518d4ab268d28b9#rd'));
                array_push($data,array('title'=>$help));
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
                $wiki=wiki($matches[2]);
                if (is_array($wiki)) {
                    $data=array();
                    for ($i=0;$i<5;$i++){ 
                        array_push($data,array('title'=>'# '.$wiki[$i]['title']."\n".'------------------------------------------','note'=>$wiki[$i]['snippet'],'cover'=>'','link'=>$wiki[$i]['link']));
                    }
                } else {
                    $data=$wiki;
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
    
                $res=$mysql->getData("SELECT alias FROM info WHERE FromUserName='{$openid}'");
                $alias=$res[0]['alias'];
                if (!$alias) {
                    $data='由于你是第一次发送动态，请按照以下格式回复基本信息:'."\n".'昵称:+你的昵称';
                }
                else{
                    $mysql->runSql("INSERT INTO moments(FromUserName,alias,moment,time,date) VALUES ('{$openid}','{$alias}','{$moment}',{$time},'{$date}')");
                    $data="发送成功，你可以现在上传一张图片作为配图，当然也可以不上传啦。回复[动态]查看";
                }
            }
            elseif($content=='动态'){
                $res=$mysql->getData("SELECT * FROM moments ORDER BY ID DESC LIMIT 5");
                $data=array(array('title'=>'动态','cover'=>$web.'/img/meizi/2.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['alias'].':','note'=>$res[$i]['moment']."\n".$res[$i]['date'],'cover'=>$res[$i]['photo']));
                    }
                }
            }
            elseif(preg_match('/^(昵称:)(.+)/i',$content,$matches)){
                $alias=$matches[2];
                $res=$mysql->getData("SELECT * FROM info");
                $num=0;
                while ($res[$num]) {
                    if ($alias==$res[$num]['alias'] || $alias=='小u' || $alias=='urinx') {
                        $t=1;
                    }
                    elseif ($openid==$res[$num]['FromUserName']) {
                        $u=1;
                    }
                    $num++;
                }
                if ($t) {
                    $data='该用户名已被注册，请重新输入';
                }
                else{
                    if ($u) {
                        $mysql->runSql("UPDATE info SET alias='{$alias}' WHERE FromUserName='{$openid}'");
                        $data='更改成功!';
                    }
                    else{
                        $mysql->runSql("INSERT INTO info(FromUserName,alias) VALUES ('{$openid}','{$alias}')");
                        $data='注册成功!';
                    }
                }
            }
            //biaoqing
            elseif(preg_match('#(/:)#i',$content)) {
                $data=$biaoqing[rand(0,count($biaoqing)-1)];
            }
            //changeSimsimiKey
            elseif(preg_match('#^(simi:)(.+)#i',$content,$matches)) {
                $s->write('simsimi','simi.txt',$matches[2]);
                $data='更改成功！';
            }
            //stackoverflow
            elseif($content=='stack'){
                $res=stackoverflow();
                $data=array(array('title'=>'Stack Overflow','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>str_replace('-',' ',$res[$i]['title']),'note'=>'votes:'.$res[$i]['vote'].' answers:'.$res[$i]['answer'].' views:'.$res[$i]['view'],'link'=>'http://stackoverflow.com/questions/'.$res[$i]['id']));
                    }
                }
            }
            //md5
            elseif(preg_match('#^(md5:)(.+)#i',$content,$matches)) {
                $data=md5($matches[2]);
            }
            //bilibili
            elseif($content=='bilibili'){
                $res=bilibili();
                $data=array(array('title'=>'Bilibili','cover'=>$web.'/img/bili/'.mt_rand(0,10).'.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['category'].':'.$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //yyets
            elseif($content=='人人影视'){
                $res=yyets();
                $data=array(array('title'=>'人人影视','cover'=>$web.'/img/yyets.png'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //songshu
            elseif($content=='松鼠'){
                $res=songshu();
                $data=array(array('title'=>'科学松鼠会','cover'=>$web.'/img/songshu.gif'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //ifanr
            elseif($content=='爱范'){
                $res=ifanr();
                $data=array(array('title'=>'爱范儿 · Beats of Bits','cover'=>$web.'/img/ifanr.gif'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //dushu
            elseif($content=='读书'){
                $res=bookrank();
                $data=array(array('title'=>'读书排行榜','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //matrix67
            elseif($content=='matrix67'){
                $res=matrix67();
                $data=array(array('title'=>'matrix67','cover'=>$web.'/img/matrix67.png'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //freebuf
            elseif($content=='freebuf'){
                $res=freebuf();
                $data=array(array('title'=>'Freebuf','cover'=>$web.'/img/freebuf.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //nginx
            elseif($content=='运维'){
                $res=nginx();
                $data=array(array('title'=>'好的架构减少运维，好的运维反哺架构','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //shejidaren
            elseif($content=='设计达人'){
                $res=shejidaren();
                $data=array(array('title'=>'设计达人-爱设计，爱分享。','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //91ri
            elseif($content=='91ri'){
                $res=ri91();
                $data=array(array('title'=>'网络安全攻防研究室','cover'=>$web.'/img/91ri.gif'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //wooyun
            elseif($content=='乌云'){
                $res=wooyun();
                $data=array(array('title'=>'wooyun.org 最新提交漏洞','cover'=>$web.'/img/wooyun.png'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //0x50sec
            elseif($content=='0x50sec'){
                $res=sec0x50();
                $data=array(array('title'=>'Web安全手册,专注Web安全','cover'=>$web.'/img/0x50sec.png'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                    }
                }
            }
            //ghost
            elseif($content=='鬼故事'){
                foreach ($ghost as $key=>$value) {
                    $note.='                      '.$key."\n";
                }
                $data=array(array('title'=>'每日更新，关注最新、经典、热门鬼故事','cover'=>$web.'/img/ghost.jpg'));
                $note.='=============================='."\n".'回复以上关键字看你想看的鬼故事哟！';
                array_push($data, array('note'=>$note));
            }
            elseif($ghost[$content]){
                $res=ghost_story($content);
                $data=array(array('title'=>$content,'cover'=>$web.'/img/ghost2.gif'));
                for ($i=0; $i < count($data); $i++) {
                    if ($res[$i]) {
                        array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>'http://xiaouri.sinaapp.com/api/ghoststory_read.php?url='.$res[$i]['link']));
                    }
                }
            }
            elseif(preg_match('#^张震\s?(\d)?#i',$content,$matches)){
                if(isset($matches[1])){ $data=zhangzhen((int)$matches[1]); }
                else{ $data=zhangzhen(rand(0,49)); }
            }
        }
    }

    //接收图片
    elseif($type=='image'){
        $picurl=$xml->PicUrl;
        $img_data=$f->fetch($picurl);
        if($f->errno()==0){
            $img_name=$openid.date(YmdHis).'.jpg';
            $s->write('wephoto',$img_name,$img_data);
            $picUrl=$s->getUrl('wephoto',$img_name);
            $mysql->runSql("UPDATE moments SET photo='{$picUrl}' WHERE FromUserName='{$openid}'");
            $data='你的图片已上传成功!';
            $data.="\n".picRec($picurl);
        }
        else{
            $data='failed';
        }
    }
    
    //接收语音
    elseif($type=='voice'){
        $data='小u听不懂人话的说。。';
    }

    //接收事件
    elseif($type=='event'){
        $event=strtolower($xml->Event);
        if($event=='subscribe'){
            $data=$xiaoU;
        }
    }

    $mysql->closeDb();
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
        if (rand(1,10)==6){
            switch (rand(1,18)) {
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
                
                case 5:
                    $res=$mysql->getData("SELECT * FROM moments ORDER BY ID DESC LIMIT 5");
                    $data=array(array('title'=>'动态','cover'=>$web.'/img/meizi/2.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['alias'].':','note'=>$res[$i]['moment']."\n".$res[$i]['date'],'cover'=>$res[$i]['photo']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 6:
                    $res=stackoverflow();
                    $data=array(array('title'=>'Stack Overflow','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>str_replace('-',' ',$res[$i]['title']),'note'=>'votes:'.$res[$i]['vote'].' answers:'.$res[$i]['answer'].' views:'.$res[$i]['view'],'link'=>'http://stackoverflow.com/questions/'.$res[$i]['id']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 7:
                    $res=bilibili();
                    $data=array(array('title'=>'Bilibili','cover'=>$web.'/img/bili/'.mt_rand(0,10).'.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['category'].':'.$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 8:
                    $res=yyets();
                    $data=array(array('title'=>'人人影视','cover'=>$web.'/img/yyets.png'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 9:
                    $res=songshu();
                    $data=array(array('title'=>'科学松鼠会','cover'=>$web.'/img/songshu.gif'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 10:
                    $res=ifanr();
                    $data=array(array('title'=>'爱范儿 · Beats of Bits','cover'=>$web.'/img/ifanr.gif'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 11:
                    $res=bookrank();
                    $data=array(array('title'=>'读书排行榜','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 12:
                    $res=matrix67();
                    $data=array(array('title'=>'matrix67','cover'=>$web.'/img/matrix67.png'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                    
                case 13:
                    $res=freebuf();
                    $data=array(array('title'=>'Freebuf','cover'=>$web.'/img/freebuf.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 14:
                    $res=nginx();
                    $data=array(array('title'=>'好的架构减少运维，好的运维反哺架构','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 15:
                    $res=shejidaren();
                    $data=array(array('title'=>'设计达人-爱设计，爱分享。','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'cover'=>$res[$i]['cover'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 16:
                    $res=ri91();
                    $data=array(array('title'=>'网络安全攻防研究室','cover'=>$web.'/img/91ri.gif'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 17:
                    $res=wooyun();
                    $data=array(array('title'=>'wooyun.org 最新提交漏洞','cover'=>$web.'/img/wooyun.png'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                case 18:
                    $res=sec0x50();
                    $data=array(array('title'=>'Web安全手册,专注Web安全','cover'=>$web.'/img/0x50sec.png'));
                    for ($i=0; $i < count($data); $i++) {
                        if ($res[$i]) {
                            array_push($data, array('title'=>$res[$i]['title'],'note'=>$res[$i]['description'],'link'=>$res[$i]['link']));
                        }
                    }
                    exit(W::response($xml,$data,'news'));
                    break;
                
                default:
                    break;
            }
        }
        else{
            //exit(W::response($xml,simsimi($content)));
            exit(W::response($xml,xiaodoubi($content)));
        }
    }
}

?>