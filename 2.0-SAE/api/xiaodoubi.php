<?php
function xiaodoubi($words){
    $xiaodoubi_url='http://www.xiaodoubi.com/bot/chat.php';
    $fields_post=array('chat'=>$words, );
    $fields_string=http_build_query($fields_post,'&');
    
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_URL,$xiaodoubi_url);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    $backws=curl_exec($ch);
    curl_close($ch);
    
    if(preg_match('/微信/i',$backws)) {
        $backws='该消息被防火长城,不对。。是被小u消音了，原因你懂的。。保护你的身体，有爱你的健康'.'/:B-)';
        $backws.="\n如果你发现有害身心健康的信息，请及时截图报告小u，小u全力进行封杀。";
    }
    
    return $backws;
}

echo xiaodoubi('haha');
?>