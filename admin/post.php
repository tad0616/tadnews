<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: post.php,v 1.2 2008/06/25 06:35:58 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "admin_header.php";

$xoopsOption['template_main'] = "tadnews_post_tpl.html";
/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$nsn = (!isset($_REQUEST['nsn']))? 0:intval($_REQUEST['nsn']);

switch($op){

	//新增資料
	case "insert_tad_news":
	$nsn=tadnews::insert_tad_news();
	break;
	
	//輸入表格
	case "tad_news_form";
	tadnews::tad_news_form($nsn);
	break;
		
	//更新資料
	case "update_tad_news";
	tadnews::update_tad_news($nsn);
	header("location: ../index.php?nsn={$nsn}");
	break;
	

	default:
	tadnews::tad_news_form($nsn);
	break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";

?>
