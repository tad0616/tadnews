<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: function.php,v 1.5 2008/07/03 07:15:41 tad Exp $
// ------------------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/modules/tadnews/up_file.php";
include_once XOOPS_ROOT_PATH."/modules/tadnews/class/tadnews.php";
include_once "block_function.php";


//取得電子報設定資料
function get_newspaper_set($nps_sn=""){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsConfig;
	$sql = "select * from ".$xoopsDB->prefix("tad_news_paper_setup")." where nps_sn='{$nps_sn}'";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//取得電子報資料
function get_newspaper($npsn=""){
	global $xoopsDB,$xoopsModule,$xoopsUser,$xoopsConfig;
	$sql = "select * from ".$xoopsDB->prefix("tad_news_paper")." where npsn='{$npsn}'";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


//預覽電子報
function preview_newspaper($npsn=""){
	global $xoopsDB;
	if(empty($npsn))return;
	$np=get_newspaper($npsn);
	$sql = "select title,head,foot,themes from ".$xoopsDB->prefix("tad_news_paper_setup")." where nps_sn='{$np['nps_sn']}'";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
	list($title,$head,$foot,$themes)=$xoopsDB->fetchRow($result);

  $myts =& MyTextSanitizer::getInstance();
  $title=$myts->htmlSpecialChars($title);
  $np['np_title']=$myts->htmlSpecialChars($np['np_title']);
  $np['np_content']=$myts->displayTarea($np['np_content'],1,1,1,1,0);
      
	$head=str_replace('{N}',$np['number'],$head);
	$head=str_replace('{D}',substr($np['np_date'],0,10),$head);
	$head=str_replace('{T}',$np['np_title'],$head);

	$filename = _TADNEWS_NSP_THEMES_PATH."/{$themes}/index.html";
	$handle = fopen($filename, "rb");
	$contents = '';
	while (!feof($handle)) {
	  $contents .= fread($handle, 8192);
	}
	fclose($handle);
	$main=str_replace("{TNP_THEME}",_TADNEWS_NSP_THEMES_URL."/{$themes}/",$contents);
	$main=str_replace("{TNP_CSS}","",$main);
	$main=str_replace("{TNP_TITLE}",$title,$main);
	$char=_CHARSET;
	$main=str_replace("{TNP_CODE}",$char,$main);
	$main=str_replace("{TNP_HEAD}",$head,$main);
	$main=str_replace("{TNP_FOOT}",$foot,$main);
	$main=str_replace("{TNP_URL}",XOOPS_URL."/modules/tadnews/newspaper.php?npsn={$npsn}",$main);
	$main=str_replace("{TNP_CONTENT}",$np['np_content'],$main);
	return $main;
}


//輸出為UTF8
function to_utf8($buffer=""){
	if(_CHARSET=="UTF-8"){
		return $buffer;
	}else{
  	$buffer=iconv(_CHARSET,"UTF-8",$buffer);
  	return $buffer;
	}
}

?>
