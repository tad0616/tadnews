<?php
//°Ï¶ô¥D¨ç¦¡ (QR Code)
function tadnews_qrcode_show($options){
  if(preg_match("/tadnews\/index.php\?nsn=/i", $_SERVER['REQUEST_URI'])){
    $url=str_replace("index.php","pda.php",$_SERVER['REQUEST_URI']);
  }elseif(preg_match("/tadnews\/index.php\?ncsn=/i", $_SERVER['REQUEST_URI'])){
    $url=str_replace("index.php","pda.php",$_SERVER['REQUEST_URI']);
  }elseif(preg_match("/tadnews\/$/i", $_SERVER['REQUEST_URI'])){
    $url=$_SERVER['REQUEST_URI']."pda.php";
  }else{
    return ;
  }

  //QRCode
  $block="";
  if(file_exists(TADTOOLS_PATH."/qrcode.php")){
    include_once TADTOOLS_PATH."/qrcode.php";
    $qrcode= new qrcode();
    $block=$qrcode->render($url);
  }
	return $block;
}
?>
