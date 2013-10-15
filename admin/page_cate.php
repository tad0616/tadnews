<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: page.php,v 1.1 2008/06/25 06:35:47 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include_once "admin_header.php";
include_once "admin_function.php";
$xoopsOption['template_main'] = "tadnews_adm_page_cate_tpl.html";
/*-----------function區--------------*/
//tad_news_cate編輯表單
function tad_news_cate_form($ncsn=""){
	global $xoopsDB,$xoopsTpl,$xoopsOption,$xoopsModuleConfig;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

	//抓取預設值
	if(!empty($ncsn)){
		$DBV=tadnews::get_tad_news_cate($ncsn);
	}else{
		$DBV=array();
	}

	//預設值設定
	$ncsn=(!isset($DBV['ncsn']))?"":$DBV['ncsn'];
	$of_ncsn=(!isset($DBV['of_ncsn']))?"":$DBV['of_ncsn'];
	$nc_title=(!isset($DBV['nc_title']))?"":$DBV['nc_title'];
	$sort=(!isset($DBV['sort']))?tadnews::get_max_sort(0,1):$DBV['sort'];
	$enable_group=(!isset($DBV['enable_group']))?"":explode(",",$DBV['enable_group']);
	$enable_post_group=(!isset($DBV['enable_post_group']))?"":explode(",",$DBV['enable_post_group']);
	$not_news=(!isset($DBV['not_news']))?"":$DBV['not_news'];
	$cate_pic=(!isset($DBV['cate_pic']))?"":$DBV['cate_pic'];
	$pic=(empty($cate_pic))?"../images/no_cover.png":_TADNEWS_CATE_URL."/{$cate_pic}";
	$setup=(!isset($DBV['setup']))?"":$DBV['setup'];
	
	$set=tadnews::get_setup($setup);
	
	$op=(empty($ncsn))?"insert_tad_news_cate":"update_tad_news_cate";
	//$op="replace_tad_news_cate";
	
	$cate_select=tadnews::get_tad_news_cate_option(0,0,$of_ncsn,$ncsn,"1","1");

	$SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false,$enable_group, 3, true);
	$SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
  $SelectGroup_name->setExtra("class='span12'");

	$enable_group = $SelectGroup_name->render();
	
	$SelectGroup_name = new XoopsFormSelectGroup("", "enable_post_group", false,$enable_post_group, 3, true);
	//$SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
  $SelectGroup_name->setExtra("class='span12'");
	$enable_post_group = $SelectGroup_name->render();


  $thumb_pic=(empty($nc_title))?"":"<img src='{$pic}' width=50 align=absmiddle hspace=4 alt='{$nc_title}' title='{$nc_title}'>";


  $catearr=list_tad_news_cate(0,"",1);
  $cate_pic_width=$xoopsModuleConfig['cate_pic_width']+10;
  $xoopsTpl->assign( "cate_pic_width" , $cate_pic_width) ;
  $xoopsTpl->assign( "jquery" , get_jquery(true)) ;
  //$xoopsTpl->assign( "list_tad_news_cate" , list_tad_news_cate(0)) ;
  $xoopsTpl->assign( "cate_select" , $cate_select) ;
  $xoopsTpl->assign( "sort" , $sort) ;
  $xoopsTpl->assign( "nc_title" , $nc_title) ;
  $xoopsTpl->assign( "enable_group" , $enable_group) ;
  $xoopsTpl->assign( "enable_post_group" , $enable_post_group) ;
  $xoopsTpl->assign( "ncsn" , $ncsn) ;
  $xoopsTpl->assign( "op" , $op) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "cate" , $catearr['arr']) ;
  $xoopsTpl->assign( "not_news" , 1) ;
  $xoopsTpl->assign( "php_self" , 'page_cate.php') ;
  
  if(!isset($set['title']))$set['title']="";
  if(!isset($set['tool']))$set['tool']="";
  if(!isset($set['comm']))$set['comm']="";
  if(!isset($set['nav']))$set['nav']="";

  $set_title_0 = chk($set['title'],"0",null,"selected");
  $set_title_1 = chk($set['title'],"1","1","selected");
  $set_tool_1 = chk($set['tool'],"1",null,"selected");
  $set_tool_0 = chk($set['tool'],"0","1","selected");
  $set_comm_1 = chk($set['comm'],"1",null,"selected");
  $set_comm_0 = chk($set['comm'],"0","1","selected");
  $set_nav_1 = chk($set['nav'],"1",null,"selected");
  $set_nav_0 = chk($set['nav'],"0","1","selected");

  $xoopsTpl->assign( "set_title_0" , $set_title_0);
  $xoopsTpl->assign( "set_title_1" , $set_title_1);
  $xoopsTpl->assign( "set_tool_0" , $set_tool_0);
  $xoopsTpl->assign( "set_tool_1" , $set_tool_1);
  $xoopsTpl->assign( "set_comm_0" , $set_comm_0);
  $xoopsTpl->assign( "set_comm_1" , $set_comm_1);
  $xoopsTpl->assign( "set_nav_0" , $set_nav_0);
  $xoopsTpl->assign( "set_nav_1" , $set_nav_1);
}



/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$ncsn = (!isset($_REQUEST['ncsn']))? "":$_REQUEST['ncsn'];
$to_ncsn = (!isset($_REQUEST['to_ncsn']))? "":intval($_REQUEST['to_ncsn']);
$not_news = (!isset($_REQUEST['not_news']))? "":intval($_REQUEST['not_news']);
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



	//搬移資料
	case "move_to":
	move_to_cate($ncsn,$to_ncsn);
	header("location: ".$_SERVER['PHP_SELF']);
	break;

	//分類類型互轉
	case "change_kind":
	change_kind($ncsn,$not_news);
	break;
	
	default:
  tad_news_cate_form($ncsn);
	break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
?>
