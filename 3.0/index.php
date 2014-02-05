<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');

$appdir=dirname(__FILE__);
require $appdir . '/wechat-api.php';

$mysql=new SaeMysql();
$s = new SaeStorage();
$f = new SaeFetchurl();
########################Constant########################
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
$pythonWelcome='Python 2.7.5 (default, Aug 25 2013, 00:04:04)
[GCC 4.2.1 Compatible Apple LLVM 5.0 (clang-500.0.68)] on darwin
Type "help", "copyright", "credits" or "license" for more information.';
$mysqlWelcome="Welcome to the MySQL monitor.  Commands end with ; or \g.
Server version: 5.0.45-community-nt MySQL Community Edition (GPL)
Type 'help;' or '\h' for help. Type '\c' to clear the buffer.";
$sqliWelcome="sqli beta1.0\nWelcome to the sqli platform for you to leran SQL injection.We wish you enjoin this and have fun.\n\nsend 'login' to start.";
$welcome=array($pythonWelcome,$mysqlWelcome,$sqliWelcome);
$help='这是目前小u有的功能:'."\n".'-----------------'."\n".'[小u]'."\n".':查看小u的基本信息，以及功能介绍和近期更新'."\n".'-----------------'."\n".'[帮助]'."\n".':查看使用帮助'."\n".'-----------------'."\n".'[查水表]'."\n".':查询寝室的水电费(华科)'."\n".'-----------------'."\n".'[每日一句]'."\n".':每天更新一句英语，中英对照'."\n".'-----------------'."\n".'[点歌]'."\n".':小u每天会为大家推荐好的歌曲，希望大家喜欢。如果想给某人点歌的话，可以直接跟我说哦'."\n".'-----------------'."\n".'[笑话]'."\n".':郁闷时看看笑话吧，小u这里有好多笑话等着你呢'."\n".'-----------------'."\n".'[新闻]'."\n".':没事的时候大家多看看新闻吧，小u不懈的为你奉送中'."\n".'-----------------'."\n".'[彩票]'."\n".':每天的彩票信息一目了然'."\n".'-----------------'."\n".'翻译'."\n".':发送"#+你要翻译的内容",即可收到详细结果，例如:#doofus'."\n".'-----------------'."\n".'天气+找小u'."\n".':点击下面的“+”，发送你的的位置信息，即可收到本地的天气预报，并且看到你和小u的距离哟。'."\n".'-----------------'."\n".'bing搜索'."\n".':发送"%+你要搜索的内容",即可收到详细结果，例如:%dweeb'."\n".'-----------------'."\n".'维基百科'."\n".':发送"&+你要搜索的内容",小u会根据你的输入自动判断查询中文维基或是英文,(*^__^*) 嘻嘻。例如:&spaz'."\n".'-----------------'."\n".'二维码'."\n".':发送"*+你要生成的内容",小u会返回生成的二维码。例如:*嘟嘟噜'."\n".'-----------------';
$help.="\n".'豆瓣'."\n".'1.书'."\n".' bs:关键字 搜索相关的书籍'."\n".' b:书名 查看详细内容'."\n".'2.音乐'."\n".' ms:关键字 搜索相关的音乐'."\n".' m:音乐名 查看详细内容'."\n".'3.电影'."\n".' vs:关键字 搜索相关的电影'."\n".' v:电影名 查看详细内容'."\n".'-----------------';
$help.="\n".'动态'."\n".':发送":+你要分享的文字"即可，大家可以回复[动态]查看，都可以看到哦。例如 :这是我发的第一个说说'."\n".'-----------------';
$help.="\n".'美女识别'."\n".':上传图片，看看小u的眼力吧'."\n".'-----------------'."\n".'周公解梦'."\n".':发送"梦到xxx",小u来预测吉凶，例如"梦到小u"'."\n".'-----------------'."\n".'手机号码查询'."\n".':直接发送手机号'."\n".'-----------------';
$help.="\n".'#[xx]内的内容xx是指你发送给小u的';
$waterbiao='查询电费请点击这里：'."\n".'<a href="http://42.120.22.130/dianfei.php">查电费</a>。'."\n".'低余电费自动提醒功能请点击这里：'."\n".'<a href="http://42.120.22.130:8822/">邮件提醒</a>';
$terminal=array('>python'=>1,'>mysql'=>2,'>sqli'=>3);
$keywords=array(
    '小u'=>$xiaoU,
    '点歌'=>array('title'=>'你给的甜', 'description'=>'何艺纱', 'musicurl'=>'http://data7.5sing.com/T1aMbeBXbT1R47IVrK.mp3', 'HQmusicurl'=>'http://data7.5sing.com/T1aMbeBXbT1R47IVrK.mp3'),
    '每日一句'=>en_sentenceAPI(),
    '笑话'=>jokes(),
    '新闻'=>baiduNews(),
    '动态'=>moment(),
    'bilibili'=>bilibili(),
    'stack'=>stackoverflow(),
    '小幽'=>'在！',
    '彩票'=>lottery(),
    '帮助'=>$help,
    '查水表'=>$waterbiao,
    );
$rss_arr=array(
    '0x50sec'=>array('title'=>'Web安全手册,专注Web安全','cover'=>$web.'/img/0x50sec.png'),
    '91ri'=>array('title'=>'网络安全攻防研究室','cover'=>$web.'/img/91ri.gif'),
    'freebuf'=>array('title'=>'Freebuf','cover'=>$web.'/img/freebuf.jpg'),
    'matrix67'=>array('cover'=>$web.'/img/matrix67.png'),
    '读书'=>array('title'=>'读书排行榜','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'),
    '松鼠'=>array('title'=>'科学松鼠会','cover'=>$web.'/img/songshu.gif'),
    '爱范'=>array('title'=>'爱范儿 · Beats of Bits','cover'=>$web.'/img/ifanr.gif'),
    '人人影视'=>array('title'=>'人人影视','cover'=>$web.'/img/yyets.png'),
    '设计达人'=>array('title'=>'设计达人-爱设计，爱分享。','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'),
    '运维'=>array('title'=>'好的架构减少运维，好的运维反哺架构','cover'=>$web.'/img/meizi/'.mt_rand(0,9).'.jpg'),
    '乌云'=>array('title'=>'wooyun.org 最新提交漏洞','cover'=>$web.'/img/wooyun.png'),
    );
$reg_arr=array(
    '/^(梦到)(.+)/i'=>'dream',
    '/^(#)(.+)/i'=>'translate',
    '#(/:)#i'=>'biaoqingbiaoqing',
    '#^(simi:)(.+)#i'=>'changeSimsimiKey',
    '#^(md5:)(.+)#i'=>'md5',
    '/^(\*)(.+)/i'=>'qrcode',
    '/^(%)(.+)/i'=>'bingSearch',
    '/^(&)(.+)/i'=>'wiki',
    '/^(vs:)(.+)/i'=>'doubanMovie1',
    '/^(v:)(.+)/i'=>'doubanMovie2',
    '/^(bs:)(.+)/i'=>'doubanBook1',
    '/^(b:)(.+)/i'=>'doubanBook2',
    '/^(ms:)(.+)/i'=>'doubanMusic1',
    '/^(m:)(.+)/i'=>'doubanMusic2',
    '/^(:)(.+)/i'=>'moments1',
    '/^(昵称:)(.+)/i'=>'moments2',
    );
########################################################
//Define function
/*---------------------------------------------------*/
function changeStatu($openid,$num){
    $mysql=new SaeMysql();
    $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
    if (!$result[0]){
        $mysql->runSql("INSERT INTO python(FromUserName,state) VALUES ('{$openid}',{$num})");
    }
    else{
        $mysql->runSql("UPDATE python SET state={$num} WHERE FromUserName='{$openid}'");
    }
}
function moment(){
    $mysql=new SaeMysql();
    $res=$mysql->getData("SELECT * FROM moments ORDER BY ID DESC LIMIT 5");
    $data=array(array('title'=>'动态','cover'=>$web.'/img/meizi/2.jpg'));
    for ($i=0; $i < count($data); $i++) {
        if ($res[$i]) {
            array_push($data, array('title'=>$res[$i]['alias'].':','note'=>$res[$i]['moment']."\n".$res[$i]['date'],'cover'=>$res[$i]['photo']));
        }
    }
    return $data;
}
function mysqlc($content){
    $mysql=new SaeMysql();
    if($content=='help'){
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
    return $data;
}
function sqli($content){
    if($content=='login'){
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
    return $data;
}
function terminalEngine($content,$openid){
    global $terminal,$welcome;
    $mysql=new SaeMysql();
    $result=$mysql->getData("SELECT * FROM python WHERE FromUserName='{$openid}'");
    if (!$result[0]['state']) {
        if ($terminal[$content]){
            changeStatu($openid,$terminal[$content]);
            return $welcome[$terminal[$content]-1];
        }
    }
    elseif ($content=='quit'){
        $mysql->runSql("UPDATE python SET state=0 WHERE FromUserName='{$openid}'");
        return '已退出终端...';
    }
    else{
        switch ($result[0]['state']) {
            case 1://python
                $data=python($content);
                break;

            case 2://mysql
                $data=mysqlc($content);
                break;

            case 3://sqli
                $data=sqli($content);
                break;
            
            default:
                # code...
                break;
        }
        return $data;
    }
}
function keywordsEngine($content){
    $content=strtolower(trim($content));
    global $keywords,$rss_arr;
    if ($keywords[$content]) {
        $data=$keywords[$content];
    }
    elseif ($rss_arr[$content]) {
        $data=rss($content);
        array_unshift($data,$rss_arr[$content]);
    }
    else{
        if (ctype_alpha($content)) {
            $data=translateAPI($content);
        }
    }
    return $data;
}
function matchEngine($content){
    global $reg_arr,$biaoqing;
    foreach ($reg_arr as $key => $value) {
        if(preg_match($key, $content, $matches)){
            switch ($value) {
                case 'dream':
                    $data=dream($matches[2]);
                    break;
                
                case 'translate':
                    $data=translateAPI($matches[2]);
                    break;

                case 'biaoqing':
                    $data=$biaoqing[rand(0,count($biaoqing)-1)];
                    break;

                case 'changeSimsimiKey':
                    $s = new SaeStorage();
                    $s->write('simsimi','simi.txt',$matches[2]);
                    $data='更改成功！';
                    break;

                case 'md5':
                    $data=md5($matches[2]);
                    break;

                case 'qrcode':
                    $qrcode='http://chart.apis.google.com/chart?cht=qr&chs=400x400&choe=UTF-8&chl='.$matches[2];
                    $data=array(
                        array('title'=>$matches[2],'cover'=>$qrcode,'link'=>$qrcode)
                    );
                    break;

                case 'bingSearch':
                    $bingweb=bing($matches[2],'Web');
                    $bingimage=bing($matches[2],'Image');
                    $data=array(
                        array('title'=>$matches[2],'note'=>'','cover'=>$bingimage[0],'link'=>'')
                    );
                    //max:7
                    for ($i=0;$i<5;$i++){ 
                        array_push($data,array('title'=>$bingweb[$i]['title']."\n".'------------------------------------------','note'=>$bingweb[$i]['description'],'cover'=>'','link'=>$bingweb[$i]['url']));
                    }
                    break;

                case 'wiki':
                    $wiki=wiki($matches[2],$lng);
                    $data=array();
                    for ($i=0;$i<5;$i++){ 
                        array_push($data,array('title'=>$wiki[$i]['title']."\n".'------------------------------------------','note'=>$wiki[$i]['snippet'],'cover'=>'','link'=>'http://zh.wikipedia.org/wiki/'.$wiki[$i]['title']));
                    }
                    break;

                case 'doubanMovie1':
                    $m=doubanMovies($matches[2]);
                    $data=array(array('title'=>$m[0]['title'],'note'=>$m[0]['year'].' '.$m[0]['average'],'cover'=>$m[0]['images']->large,'link'=>$m[0]['alt']));
                    for ($i=1;$i<=5;$i++){ 
                        array_push($data,array('title'=>$m[$i]['title'],'note'=>'又名:'.$m[$i]['original_title']."\n".'上映日期:'.$m[$i]['year']."\n".'评价:'.$m[$i]['average'],'cover'=>$m[$i]['images']->small,'link'=>$m[$i]['alt']));
                    }
                    break;

                case 'doubanMovie2':
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
                    break;

                case 'doubanBook1':
                    $b=doubanBooks($matches[2]);
                    $data=array(array('title'=>$b[0]['title'],'note'=>$b[0]['author'][0].' '.$b[0]['average'],'cover'=>$b[0]['images']->large,'link'=>$b[0]['alt']));
                    for ($i=1;$i<=4;$i++){ 
                        array_push($data,array('title'=>$b[$i]['title'],'note'=>'作者:'.$b[$i]['author'][0]."\n".'出版社:'.$b[$i]['publisher']."\n".'出版日期:'.$b[$i]['pubdate']."\n".'价格:'.$b[$i]['price']."\n".'评价:'.$b[$i]['average']."\n".'导言:'.$b[$i]['summary'],'cover'=>$b[$i]['images']->small,'link'=>$b[$i]['alt']));
                    }
                    break;

                case 'doubanBook2':
                    $b=doubanBooks($matches[2]);
                    $bk=doubanBook($b[0]['id']);
                    $data=array(
                        array('title'=>$bk['title'],'cover'=>$bk['images'],'link'=>$bk['alt']),
                        array('note'=>'作者:'.$bk['author'][0]."\n".'出版社:'.$bk['publisher']."\n".'出版日期:'.$bk['pubdate']."\n".'价格:'.$bk['price']."\n".'评价:'.$bk['average'],'link'=>$bk['alt']),
                        array('title'=>'导言:','note'=>$bk['summary'],'link'=>$bk['alt'])
                    );
                    break;

                case 'doubanMusic1':
                    $m=doubanMusics($matches[2]);
                    $data=array(array('title'=>$m[0]['title'],'note'=>$m[0]['average'],'cover'=>$m[0]['image'],'link'=>$m[0]['alt']));
                    for ($i=1;$i<=4;$i++){ 
                        array_push($data,array('title'=>$m[$i]['title'],'note'=>'作者:'.$m[$i]['author'][0]->name."\n".'出版社:'.$m[$i]['publisher'][0]."\n".'出版日期:'.$m[$i]['pubdate'][0]."\n".'表演者:'.$m[$i]['singer'][0]."\n".'评价:'.$m[$i]['average']."\n".'类型:'.$m[$i]['version'][0],'cover'=>$m[$i]['image'],'link'=>$m[$i]['alt']));
                    }
                    break;

                case 'doubanMusic2':
                    $m=doubanMusics($matches[2]);
                    $mu=doubanMusic($m[0]['id']);
                    $data=array(
                        array('title'=>$mu['title'],'cover'=>$mu['image'],'link'=>$mu['mobile_link']),
                        array('note'=>'表演者:'.$mu['singer'][0]."\n".'专辑类型:'.$mu['version'][0]."\n".'发行时间:'.$mu['pubdate'][0]."\n".'作者:'.$mu['author'][0]->name."\n".'出版者:'.$mu['publisher'][0]."\n".'评价:'.$mu['average'],'link'=>$mu['mobile_link']),
                        array('title'=>'简介:','note'=>$mu['summary'],'link'=>$mu['mobile_link'])
                    );
                    break;

                case 'moments1':
                    $moment=$matches[2];
                    $time=time();
                    $date=date("Y-m-d H:i:s",time());
                    $mysql=new SaeMysql();
                    $res=$mysql->getData("SELECT alias FROM info WHERE FromUserName='{$openid}'");
                    $alias=$res[0]['alias'];
                    if (!$alias) {
                        $data='由于你是第一次发送动态，请按照以下格式回复基本信息:'."\n".'昵称:+你的昵称';
                    }
                    else{
                        $mysql->runSql("INSERT INTO moments(FromUserName,alias,moment,time,date) VALUES ('{$openid}','{$alias}','{$moment}',{$time},'{$date}')");
                        $data="发送成功，你可以现在上传一张图片作为配图，当然也可以不上传啦。回复[动态]查看";
                    }
                    break;

                case 'moments2':
                    $alias=$matches[2];
                    $mysql=new SaeMysql();
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
                    break;

                default:
                    # code...
                    break;
            }
        }
    }
    return $data;
}
function randEngine(){
    global $keywords,$rss_arr;
    for ($i=0; $i < 4; $i++) { 
        array_pop($keywords);
    }
    $a=count($keywords);
    $b=count($rss_arr);
    if (rand(1,$a+$b)<=$b) {
        $key=array_rand($rss_arr,1);
        $data=rss($key);
        array_unshift($data,$rss_arr[$key]);
    }
    else{
        $data=$keywords[array_rand($keywords,1)];
    }
    return $data;
}
/*---------------------------------------------------*/
if(W::isPOST()){
    $post=$GLOBALS["HTTP_RAW_POST_DATA"];
    $xml=simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);
    $content=trim($xml->Content);
    $type=strtolower($xml->MsgType);
    $openid=$xml->FromUserName;
    
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
        //$data=terminalEngine($content,$openid);
        $data=keywordsEngine($content);
        $data=matchEngine($content);
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
            $data=randEngine();
            if (is_array($data)) {
                if ($data['musicurl']) {
                    exit(W::response($xml,$data,'music'));
                }
                exit(W::response($xml,$data,'news'));
            }
            else{exit(W::response($xml,$data));}
        }
        else{
            $simiword=simsimi($content);
            if ($simiword=='404') {
                $data=randEngine();
                if (is_array($data)) {
                    if ($data['musicurl']) {
                        exit(W::response($xml,$data,'music'));
                    }
                    exit(W::response($xml,$data,'news'));
                }
                else{exit(W::response($xml,$data));}
            }
            exit(W::response($xml,$simiword));
        }
    }
}

?>