<?php
$s = new SaeStorage();
#$s->upload( 'example' , 'remote_file.txt' , 'local_file.txt' );
 
#echo $s->read( 'example' , 'thebook') ;
// will echo 'bookcontent!';
 
#echo $s->getUrl( 'example' , 'thebook' );
// will echo 'http://appname-example.stor.sinaapp.com/thebook';
//$s->write('simsimi','simi.txt','f306b663-b937-47e3-a4a8-415c7ca87a52');
echo $s->read('simsimi','simi.txt');
?>
