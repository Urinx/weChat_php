<?php
header('Content-type:text/html;charset=utf-8');
function UriSql($a,$arr=null){
    $mysql=new SaeMysql();
    if($a=='s'){
        $sql="SELECT * FROM moments ORDER BY ID DESC LIMIT 5";
        $data=$mysql->getData($sql);
        return $data;
    }
    elseif($a=='i'){
        $sql="INSERT INTO info(FromUserName,alias) VALUES ('23123','dgsdfr')";
		$mysql->runSql($sql);
    }
    elseif($mysql->errno()!=0){
        die("Error:".$mysql->errmsg());
    }
    $mysql->closeDb();
}

//////////////
#define(‘DB_HOST’,$_SERVER['HTTP_MYSQLPORT'].’.mysql.sae.sina.com.cn:’.$_SERVER['HTTP_MYSQLPORT']);
#$query=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);

$mysql=new SaeMysql();

#$sql="SELECT * FROM `sharelinks` ORDER BY `ID` DESC LIMIT 4";
#$data=$mysql->getData($sql);
#print_r($data);
#$title='dudulu';
#$url='www';
#$sql="INSERT INTO `sharelinks` (`title`,`time`,`url`) VALUES ('".$title."',NOW(),'".$url."')";
#$mysql->runSql($sql);
if($mysql->errno()!=0){
    die("Error:".$mysql->errmsg());
}
$mysql->closeDb();
?>
