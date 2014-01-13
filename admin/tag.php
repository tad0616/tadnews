<?php

/*-----------引入檔案區--------------*/
include_once "admin_header.php";
include_once "admin_function.php";
$xoopsOption['template_main'] = "tadnews_adm_tag_tpl.html";
/*-----------function區--------------*/
//tad_news_tagss編輯表單
function list_tad_news_tags($def_tag_sn=""){
  global $xoopsDB,$xoopsTpl,$tadnews;

  $sql = "select * from ".$xoopsDB->prefix("tad_news_tags")."";
  $result = $xoopsDB->query($sql) or redirect_header("index.php",3,mysql_error());
  $i=0;
  $tags_used_amount=tags_used_amount();
  while(list($tag_sn,$tag,$color,$enable)=$xoopsDB->fetchRow($result)){
    $tag_amount=intval($tags_used_amount[$tag_sn]);
    $enable_btn=($enable=='1')?"<a href='tag.php?op=stat&enable=0&tag_sn=$tag_sn' class='btn btn-warning'>"._MA_TADNEWS_TAG_UNABLE."</a>":"<a href='tag.php?op=stat&enable=1&tag_sn=$tag_sn' class='btn btn-success'>"._MA_TADNEWS_TAG_ABLE."</a>";


    $del=($enable!='1' and empty($tag_amount))?"<a href='javascript:delete_tag($tag_sn);' class='btn btn-danger'>"._TADNEWS_DEL."</a>":"";

    $tagarr[$i]['prefix_tag']=$tadnews->mk_prefix_tag($tag_sn,'all');
    $tagarr[$i]['tag']=$tag;
    $tagarr[$i]['color']=$color;
    $tagarr[$i]['enable_txt']=($enable=='1')?_YES:_NO;
    $tagarr[$i]['tool']="<a href='tag.php?tag_sn=$tag_sn' class='btn btn-info'>"._TADNEWS_EDIT."</a> $enable_btn $del";
    $tagarr[$i]['mode']=($def_tag_sn==$tag_sn)?"edit":"show";
    $tagarr[$i]['enable_1']=($def_tag_sn==$tag_sn)?chk($enable,'1','1'):"";
    $tagarr[$i]['enable_0']=($def_tag_sn==$tag_sn)?chk($enable,'0'):"";
    $tagarr[$i]['amount']=sprintf(_MA_TADNEWS_TAG_AMOUNT,$tag_amount);
    $i++;

  }

  $xoopsTpl->assign( "tag_sn" , $def_tag_sn) ;
  $xoopsTpl->assign( "tagarr" , $tagarr) ;
  $xoopsTpl->assign( "jquery" , get_jquery()) ;
  $xoopsTpl->assign( "tag" , $tag) ;
  $xoopsTpl->assign( "color" , $color) ;
  $xoopsTpl->assign( "enable1" , chk($enable,'1','1')) ;
  $xoopsTpl->assign( "enable0" , chk($enable,'0')) ;
  return $main;
}

function insert_tad_news_tags(){
  global $xoopsDB;

  $sql = "insert into ".$xoopsDB->prefix("tad_news_tags")."  (`tag` , `color` , `enable`) values('{$_POST['tag']}', '{$_POST['color']}', '{$_POST['enable']}') ";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
}




function update_tad_news_tags($tag_sn){
  global $xoopsDB;

  $sql = "update ".$xoopsDB->prefix("tad_news_tags")."  set  tag = '{$_POST['tag']}',color = '{$_POST['color']}',enable = '{$_POST['enable']}' where tag_sn='{$tag_sn}'";

  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
}



function tad_news_tags_stat($enable,$tag_sn){
  global $xoopsDB;

  $sql = "update ".$xoopsDB->prefix("tad_news_tags")."  set enable = '{$enable}' where tag_sn='{$tag_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
}

function del_tag($tag_sn=""){
  global $xoopsDB;

  $sql = "delete from ".$xoopsDB->prefix("tad_news_tags")." where tag_sn='{$tag_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));

}

function tags_used_amount(){
  global $xoopsDB,$xoopsTpl;

  $sql = "select prefix_tag,count(prefix_tag) from ".$xoopsDB->prefix("tad_news")." group by prefix_tag ";
  $result = $xoopsDB->query($sql) or redirect_header("index.php",3,mysql_error());
  $main="";
  while(list($prefix_tag,$count)=$xoopsDB->fetchRow($result)){
    $main[$prefix_tag]=$count;
  }
  return $main;
}
/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$tag_sn = (!isset($_REQUEST['tag_sn']))? "":intval($_REQUEST['tag_sn']);

switch($op){

  //新增資料
  case "insert_tad_news_tags":
  $tag_sn=insert_tad_news_tags();
  header("location: ".$_SERVER['PHP_SELF']);
  break;

  //更新資料
  case "update_tad_news_tags";
  update_tad_news_tags($tag_sn);
  header("location: ".$_SERVER['PHP_SELF']);
  break;

  //關閉資料
  case "stat";
  tad_news_tags_stat($_GET['enable'],$tag_sn);
  header("location: ".$_SERVER['PHP_SELF']);
  break;

  //刪除資料
  case "del_tag";
  del_tag($tag_sn);
  header("location: ".$_SERVER['PHP_SELF']);
  break;


  default:
  list_tad_news_tags($tag_sn);
  break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";

?>
