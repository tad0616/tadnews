<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: index.php,v 1.3 2008/06/25 06:35:58 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "admin_header.php";
include_once "admin_function.php";
$xoopsOption['template_main'] = "tadnews_adm_list_tpl.html";
/*-----------function區--------------*/


/*-----------執行動作判斷區----------*/
$op = (empty($_REQUEST['op']))? "":$_REQUEST['op'];
$ncsn = (empty($_REQUEST['ncsn']))? "":intval($_REQUEST['ncsn']);
$nsn = (empty($_REQUEST['nsn']))? "":intval($_REQUEST['nsn']);
$show_uid = (empty($_REQUEST['show_uid']))? "":$_REQUEST['show_uid'];

switch($op){

	//刪除資料
	case "delete_tad_news":
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
	list_tad_news($ncsn,"news",$show_uid);
	break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
?>
