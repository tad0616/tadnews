<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: page.php,v 1.1 2008/06/25 06:35:47 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "admin_header.php";
include_once "admin_function.php";
$xoopsOption['template_main'] = "tadnews_adm_page_tpl.html";
/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$ncsn = (!isset($_REQUEST['ncsn']))? "":$_REQUEST['ncsn'];
$to_ncsn = (!isset($_REQUEST['to_ncsn']))? "":intval($_REQUEST['to_ncsn']);
$nsn = (!isset($_REQUEST['nsn']))? "":intval($_REQUEST['nsn']);
$show_uid = (empty($_REQUEST['show_uid']))? "":$_REQUEST['show_uid'];

switch($op){

	//新增資料
	case "insert_tad_news_cate":
	$ncsn=insert_tad_news_cate();
	header("location: ".$_SERVER['PHP_SELF']);
	break;
	
	//更新資料
	case "update_tad_news_cate";
	update_tad_news_cate($ncsn);
	header("location: ".$_SERVER['PHP_SELF']);
	break;
	
	//刪除資料
	case "delete_tad_news_cate";
	delete_tad_news_cate($ncsn);
	header("location: ".$_SERVER['PHP_SELF']);
	break;
	

	//刪除資料
	case "delete_tad_news";
  $tadnews=new tadnews();
	$tadnews->delete_tad_news($nsn);
	header("location: ".$_SERVER['PHP_SELF']);
	break;
	

	//批次管理
	case "batch":
	if($_POST['act']=="move_news"){
    move_news($_POST['nsn_arr'],$ncsn);
  }elseif($_POST['act']=="del_news"){
    del_news($_POST['nsn_arr']);
  }
	header("location: ".$_SERVER['PHP_SELF']);
	break;
	
	default:
  list_tad_news($ncsn,"page",$show_uid) ;

	break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
?>
