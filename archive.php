<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "tadnews_archive_tpl.html";
include_once XOOPS_ROOT_PATH."/header.php";


/*-----------function區--------------*/

//列出月份
function month_list($now_date=""){
  global $xoopsDB,$xoopsTpl;

  $sql = "select left(start_day,7) , count(*) from ".$xoopsDB->prefix("tad_news")." where enable='1' group by left(start_day,7) order by start_day desc";



  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
  $i=1;
  while(list($ym,$count)=$xoopsDB->fetchRow($result)){
    $opt[$i]['value']=$ym;
    $opt[$i]['count']=$count;
    $opt[$i]['text']=str_replace("-",""._MD_TADNEWS_YEAR,$ym)._MD_TADNEWS_MONTH;
    $opt[$i]['selected']=$now_date==$ym?"selected":"";
    $i++;
  }



  $jquery=get_jquery();
  $xoopsTpl->assign( "jquery" , $jquery) ;
  $xoopsTpl->assign( "opt" , $opt) ;
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;

}


//分月新聞
function archive($date=""){
  global $xoopsModuleConfig,$xoopsTpl,$interface_menu;

  if(empty($date)){
    $date=date("Y-m");
  }


  $tadnews=new tadnews();
  //$tadnews->set_show_num($xoopsModuleConfig['show_num']);
  $tadnews->set_news_kind("news");
  $tadnews->set_show_mode('list');
  $tadnews->set_show_month($date);
  $tadnews->set_show_enable(1);
  $tadnews->get_news();
  $xoopsTpl->assign( "bootstrap" , get_bootstrap()) ;
  $xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
  $date_title=to_utf8(str_replace("-",""._MD_TADNEWS_YEAR." ",$date)._MD_TADNEWS_MONTH._MD_TADNEWS_NEWS_TITLE);
  $xoopsTpl->assign( "date_title" , $date_title) ;

}

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$date= (!isset($_REQUEST['date']))? date("Y-m"):substr($_REQUEST['date'],0,7);
switch($op){


  //下載檔案
  case "tufdl":
  $files_sn=isset($_GET['files_sn'])?intval($_GET['files_sn']):"";
  $TadUpFiles->add_file_counter($files_sn,$hash=false);
  exit;
  break;



  default:
  month_list($date);
  archive($date);
  break;
}


/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
