<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tadnews_post_tpl.html";
include XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/
if(empty($xoopsUser))redirect_header("index.php",3, _MD_TADNEWS_NO_POST_POWER);

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$nsn = (!isset($_REQUEST['nsn']))? "":intval($_REQUEST['nsn']);
$ncsn = (!isset($_REQUEST['ncsn']))? "":intval($_REQUEST['ncsn']);

$tadnews=new tadnews();

switch($op){

  //新增資料
  case "insert_tad_news":
  //die(var_export($_REQUEST));
  $nsn=$tadnews->insert_tad_news();
  break;

  //輸入表格
  case "tad_news_form";
  $tadnews->set_news_editor($xoopsModuleConfig['editor']);
  $tadnews->tad_news_form($nsn);
  //$main.=list_tad_news(1);
  break;

  //更新資料
  case "update_tad_news";
  $tadnews->update_tad_news($nsn);
  break;


  default:
  $tadnews->set_news_editor($xoopsModuleConfig['editor']);
  $tadnews->tad_news_form($nsn,$ncsn);
  break;
}


/*-----------秀出結果區--------------*/

$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

include_once XOOPS_ROOT_PATH.'/footer.php';
?>