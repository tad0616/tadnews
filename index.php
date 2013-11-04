<?php
/*-----------引入檔案區--------------*/
include_once "header.php";

/*-----------function區--------------*/

//列出所有tad_news資料(summary模式)
function list_tad_summary_news($the_ncsn=""){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu;
  $tadnews=new tadnews();

  $tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->set_news_kind("news");
  $tadnews->set_summary("page_break");
  if($the_ncsn>0){
    $tadnews->set_view_ncsn($the_ncsn);
    $tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
  }else{
    $tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
  }
  $tadnews->set_cover(true,"db");

  //if($xoopsModuleConfig['use_star_rating']=='1'){
  //  $tadnews->set_use_star_rating(true);
  //}
  $tadnews->get_news();
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "ncsn" , $the_ncsn) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}


//列出所有tad_news資料
function list_tad_all_news($the_ncsn=""){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu;

  $tadnews=new tadnews();
  $tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->set_news_kind("news");
  if($the_ncsn>0){
    $tadnews->set_view_ncsn($the_ncsn);
    $tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
  }else{
    $tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
  }
  $tadnews->get_news();
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}


//列出所有tad_news資料
function list_tad_tag_news($tag_sn=""){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu;

  $tadnews=new tadnews();
  $tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->set_news_kind("news");
  $tadnews->set_view_tag($tag_sn);

  $tadnews->get_news();
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}


//列出所有tad_news資料
function list_tad_cate_news($show_ncsn=0,$the_level=0){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu;
  $tadnews=new tadnews();
  $tadnews->set_news_kind("news");
  $tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
  $tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->get_cate_news();
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}



//顯示單一新聞
function show_news($nsn=""){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu,$xoopsUser;

  $tadnews=new tadnews();
  $uid=($xoopsUser)?$xoopsUser->uid():"";
  $tadnews->set_show_enable(0);
  $tadnews->set_view_nsn($nsn);
  $tadnews->set_cover(true,"db");
  $tadnews->set_summary('full');
  //if($xoopsModuleConfig['use_star_rating']=='1'){
  //  $tadnews->set_use_star_rating(true);
  //}
  $tadnews->get_news();
  $xoopsTpl->assign( "uid" ,$uid) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;

}


//已經讀過
function have_read($nsn="",$uid=""){
  global $xoopsDB,$xoopsUser;
  $now=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
  $sql="insert into ".$xoopsDB->prefix("tad_news_sign")." (`nsn`,`uid`,`sign_time`) values('$nsn','$uid','{$now}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
}


//檢查置頂時間
function chk_always_top(){
  global $xoopsDB,$xoopsUser;
  $now=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));
  $sql="update ".$xoopsDB->prefix("tad_news")." set always_top='0' WHERE always_top_date <='{$now}' and always_top_date!='0000-00-00 00:00:00'";
  $xoopsDB->queryF($sql);
}


//列出簽收狀況
function list_sign($nsn=""){
  global $xoopsDB,$xoopsUser,$xoopsOption,$xoopsTpl,$interface_menu;
   $news=tadnews::get_tad_news($nsn);

   $sql="select uid,sign_time from ".$xoopsDB->prefix("tad_news_sign")." where nsn='$nsn' order by sign_time";
   $sign="";
   $i=0;
   $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
   while(list($uid, $sign_time)=$xoopsDB->fetchRow($result)){
    $uid_name=XoopsUser::getUnameFromId($uid,1);
    $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;
    $sign[$i]['uid']=$uid;
    $sign[$i]['uid_name']=$uid_name;
    $sign[$i]['sign_time']=$sign_time;
    $i++;
   }

  $xoopsTpl->assign( "news_title" , sprintf(_MD_TADNEWS_SIGN_LOG,$news["news_title"])) ;
  $xoopsTpl->assign( "nsn" , $nsn) ;
  $xoopsTpl->assign( "sign" , $sign) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}

//列出某人狀況
function list_user_sign($uid=""){
  global $xoopsDB,$xoopsUser,$xoopsOption,$xoopsTpl;
  $news=tadnews::get_tad_news($nsn);

  $uid_name=XoopsUser::getUnameFromId($uid,1);
  $uid_name=(empty($uid_name))?XoopsUser::getUnameFromId($uid,0):$uid_name;

   $sql="select a.nsn,a.sign_time,b.news_title from ".$xoopsDB->prefix("tad_news_sign")." as a left join ".$xoopsDB->prefix("tad_news")." as b on a.nsn=b.nsn where a.uid='$uid' order by a.sign_time desc";
   $sign="";
   $i=0;
   $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $myts =MyTextSanitizer::getInstance();
   while(list($nsn, $sign_time,$news_title)=$xoopsDB->fetchRow($result)){

    $news_title=$myts->htmlSpecialChars($news_title);
    $sign[$i]['nsn']=$nsn;
    $sign[$i]['news_title']=$news_title;
    $sign[$i]['sign_time']=$sign_time;
    $i++;
   }

  $xoopsTpl->assign( "uid" , $uid) ;
  $xoopsTpl->assign( "sign" , $sign) ;
  $xoopsTpl->assign( "uid_name" , sprintf(_MD_TADNEWS_SIGN_LOG,$uid_name)) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
}
/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];

$nsn=(isset($_REQUEST['nsn']))?intval($_REQUEST['nsn']) : 0;
$ncsn=(isset($_REQUEST['ncsn']))?intval($_REQUEST['ncsn']) : NULL;
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
  $tadnews=new tadnews();
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

  //把過期的置頂文徹下
  chk_always_top();
  if(!empty($nsn)){
    $xoopsOption['template_main'] = "tadnews_news_tpl.html";
    include XOOPS_ROOT_PATH."/header.php";
    show_news($nsn);
  }elseif(!empty($tag_sn)){
    $xoopsOption['template_main'] = "tadnews_list_tpl.html";
    include XOOPS_ROOT_PATH."/header.php";
    list_tad_tag_news($tag_sn);
  }elseif(!is_null($ncsn)){
    if($xoopsModuleConfig['cate_show_mode']=="summary"){
      $xoopsOption['template_main'] = "tadnews_index_summary_tpl.html";
      include XOOPS_ROOT_PATH."/header.php";
      list_tad_summary_news($ncsn);
    }else{
      $xoopsOption['template_main'] = "tadnews_list_tpl.html";
      include XOOPS_ROOT_PATH."/header.php";
      list_tad_all_news($ncsn);
    }
  }else{
    if($xoopsModuleConfig['show_mode']=="summary"){
      $xoopsOption['template_main'] = "tadnews_index_summary_tpl.html";
      include XOOPS_ROOT_PATH."/header.php";
      list_tad_summary_news();
    }elseif($xoopsModuleConfig['show_mode']=="cate"){
      $xoopsOption['template_main'] = "tadnews_index_cate_tpl.html";
      include XOOPS_ROOT_PATH."/header.php";
      list_tad_cate_news();
    }else{
      $xoopsOption['template_main'] = "tadnews_list_tpl.html";
      include XOOPS_ROOT_PATH."/header.php";
      list_tad_all_news();
    }
  }

  break;
}

/*-----------秀出結果區--------------*/
include_once XOOPS_ROOT_PATH.'/include/comment_view.php';
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
