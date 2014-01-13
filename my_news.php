<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tadnews_my_news_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";
/*-----------function區--------------*/

//列出某人所有新聞
function list_tad_my_news(){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu,$xoopsUser,$tadnews;

  $uid=$xoopsUser->uid();
  $tadnews->set_show_enable(0);
  $tadnews->set_view_uid($uid);
  $tadnews->set_news_kind($kind);
  $tadnews->set_summary(0);
  $tadnews->set_show_mode("list");
  $tadnews->set_admin_tool(true);
  $tadnews->set_show_num($xoopsModuleConfig['show_num']);

  if(!empty($the_ncsn)){
    $tadnews->set_view_ncsn($the_ncsn);
  }
  $tadnews->get_news();

  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;

}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];

$nsn=(isset($_REQUEST['nsn']))?intval($_REQUEST['nsn']) : 0;
$ncsn=(isset($_REQUEST['ncsn']))?intval($_REQUEST['ncsn']) : 0;
$fsn=(isset($_REQUEST['fsn']))?intval($_REQUEST['fsn']) : 0;
$uid=(isset($_REQUEST['uid']))?intval($_REQUEST['uid']) : "";
$kind=(empty($_REQUEST['kind']))?"":$_REQUEST['kind'];
$tag_sn=(isset($_REQUEST['tag_sn']))?intval($_REQUEST['tag_sn']) : "";

switch($op){

  //下載檔案
  case "tufdl":
  $files_sn=isset($_GET['files_sn'])?intval($_GET['files_sn']):"";
  $TadUpFiles->add_file_counter($files_sn,$hash=false);
  exit;
  break;


  //刪除資料
  case "delete_tad_news":
  $tadnews->delete_tad_news($nsn);
  header("location: ".$_SERVER['PHP_SELF']);
  break;

  //已經閱讀
  case "have_read":
  have_read($nsn,$uid);
  header("location: ".$_SERVER['PHP_SELF']."?nsn=$nsn");
  break;

  //列出簽收狀況
  case "list_sign":
  $xoopsOption['template_main'] = "tadnews_sign_tpl.html";
  include XOOPS_ROOT_PATH."/header.php";
  list_sign($nsn);
  $xoopsTpl->assign( "op" , $op) ;
  break;


  //列出某人狀況
  case "list_user_sign":
  $xoopsOption['template_main'] = "tadnews_sign_tpl.html";
  include XOOPS_ROOT_PATH."/header.php";
  list_user_sign($uid);
  $xoopsTpl->assign( "op" , $op) ;
  break;

  default:
  list_tad_my_news();
  break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/include/comment_view.php';
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
