<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_newspaper_list.php,v 1.1 2008/04/10 05:29:56 tad Exp $
// ------------------------------------------------------------------------- //

//區塊主函式 (列出最新的新聞評論)
function tadnews_newspaper_list($options){
	global $xoopsDB,$xoopsUser;

	//找出現有設定組
	$sql = "select a.npsn,a.number,b.title from ".$xoopsDB->prefix("tad_news_paper")." as a ,".$xoopsDB->prefix("tad_news_paper_setup")." as b where a.nps_sn=b.nps_sn and b.status='1' order by a.np_date desc limit 0,{$options[0]}";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
  $i=0;
  $page="";
	while(list($npsn,$number,$title)=$xoopsDB->fetchRow($result)){
		$page[$i]['npsn']=$npsn;
		$page[$i]['title']=$title.sprintf(_MB_TADNEWS_NP_TITLE,$number);
		$i++;
	}

	$block['page']=$page;
	return $block;
}

//區塊編輯函式
function tadnews_newspaper_list_edit($options){

	$form="
	"._MB_TADNEWS_NP_EDIT_BITEM0."
	<INPUT type='text' name='options[0]' value='{$options[0]}'>
	";
	return $form;
}

?>