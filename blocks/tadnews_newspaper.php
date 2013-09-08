<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: tadnews_newspaper.php,v 1.1 2008/04/10 05:29:56 tad Exp $
// ------------------------------------------------------------------------- //

//區塊主函式 (電子報訂閱)
function tadnews_newspaper($options){
	global $xoopsDB,$xoopsUser;
	
	$sql = "select count(*) from ".$xoopsDB->prefix("tad_news_paper_email")."";
	$result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
	list($counter)=$xoopsDB->fetchRow($result);
	
	//找出現有設定組
	$sql = "select nps_sn,title from ".$xoopsDB->prefix("tad_news_paper_setup")." where status='1'";
	$result=$xoopsDB->query($sql);
	$i=0;
	$option="";
	while(list($nps_sn,$title)=$xoopsDB->fetchRow($result)){
		$option[$i]['value']=$nps_sn;
		$option[$i]['text']=$title;
		$i++;
	}
  $block['counter']=sprintf(_MB_TADNEWS_ORDER_COUNT,$counter);
  $block['option']=$option;
	return $block;
}

?>