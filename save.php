<?php
include_once "header.php";

add_tad_news_cate($_POST['value']);

//新增資料到tad_news_cate中
function add_tad_news_cate($title="",$no_news='0'){
  global $xoopsDB,$xoopsModuleConfig;
  $enable_group=$enable_post_group=$setup="";
  $sql = "select max(sort) from ".$xoopsDB->prefix("tad_news_cate")." where of_ncsn=''";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3,show_error($sql));
  list($sort)=$xoopsDB->fetchRow($result);
  $sort++;

  $myts =MyTextSanitizer::getInstance();
  $title=$myts->addSlashes($title);

  $sql = "insert into ".$xoopsDB->prefix("tad_news_cate")." (of_ncsn,nc_title,enable_group,enable_post_group,sort,not_news,setup) values('0','{$title}','{$enable_group}','{$enable_post_group}','{$sort}','{$no_news}','{$setup}')";

  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, _MD_TADNEWS_DB_ADD_ERROR1);
  //取得最後新增資料的流水編號
  $ncsn=$xoopsDB->getInsertId();
  return $ncsn;
}

echo $_POST['value'];
?>
