<?php

function xoops_module_update_tadnews(&$module, $old_version) {
    GLOBAL $xoopsDB;

    if(!chk_chk9()) go_update9();
    if(!chk_chk10()) go_update10();
    if(!chk_chk11()) go_update11();
    if(!chk_chk12()) go_update12();
    if(!chk_chk13()) go_update13();
    if(!chk_chk14()) go_update14();
    if(!chk_chk15()) go_update15();
    if(!chk_chk16()) go_update16();
    if(!chk_chk17()) go_update17();
    if(!chk_chk18()) go_update18();
    if(!chk_chk19()) go_update19();
    //調整檔案上傳欄位col_sn為mediumint(9)格式
    if(chk_files_center()) go_update_files_center();
    if(chk_uid()) go_update_uid();

    $old_fckeditor=XOOPS_ROOT_PATH."/modules/tadnews/fckeditor";
    if(is_dir($old_fckeditor)){
      delete_directory($old_fckeditor);
      delete_directory(XOOPS_ROOT_PATH."/modules/tadnews/dhtmlgoodies_calendar");
    }
    return true;
}

//新增置頂日期欄位
function chk_chk9(){
  global $xoopsDB;
  $sql="select count(`always_top_date`) from ".$xoopsDB->prefix("tad_news");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update9(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_news")." ADD `always_top_date` DATETIME NOT NULL";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30, show_error($sql));
}


//建立搬移檔案新表格
function chk_chk10(){
  global $xoopsDB;
  $sql="select count(`col_name`) from ".$xoopsDB->prefix("tadnews_files_center");
  //$sql="SHOW FIELDS FROM ".$xoopsDB->prefix("tadnews_files_center");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update10(){
  global $xoopsDB;
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews");
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/cate");
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/file");
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/image");
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/image/.thumbs");

  //建立電子報佈景
  mk_dir(XOOPS_ROOT_PATH."/uploads/tadnews/themes");
  if(!is_dir(XOOPS_ROOT_PATH."/uploads/tadnews/themes/bluefreedom2")){
    full_copy(XOOPS_ROOT_PATH."/modules/tadnews/images/bluefreedom2", XOOPS_ROOT_PATH."/uploads/tadnews/themes/bluefreedom2");
  }

  //表格改名
  $sql="RENAME TABLE ".$xoopsDB->prefix("tad_news_files")."  TO ".$xoopsDB->prefix("tadnews_files_center");
  $xoopsDB->queryF($sql);

  //修改表格
  $sql="ALTER TABLE `".$xoopsDB->prefix("tadnews_files_center")."`
  CHANGE `fsn` `files_sn` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT,
  CHANGE `nsn` `col_sn` SMALLINT( 5 ) UNSIGNED NOT NULL,
  ADD `col_name` VARCHAR( 255 ) NOT NULL AFTER `files_sn` ,
  ADD `sort` SMALLINT UNSIGNED NOT NULL AFTER `col_sn` ,
  ADD `kind` ENUM( 'img', 'file' ) NOT NULL AFTER `sort`,
  ADD `description` TEXT NOT NULL AFTER `file_type`";
  $xoopsDB->queryF($sql) or die($sql."<br>".mysql_error());

  //套入描述以及欄位名稱
  $sql="update ".$xoopsDB->prefix("tadnews_files_center")." set `col_name`='nsn', `description`=`file_name`";

  $xoopsDB->queryF($sql);


  $sql="select files_sn,file_name,file_type,description,col_name,col_sn from ".$xoopsDB->prefix("tadnews_files_center")."";
  $result=$xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30, show_error($sql));
  while(list($files_sn,$file_name,$file_type,$description,$col_name,$col_sn)=$xoopsDB->fetchRow($result)){
    $kind=(substr($file_type,0,5)=="image")?"img":"file";
    $new_file_name="{$col_name}_{$col_sn}_{$files_sn}".substr($description,-4);
    if($kind=="file"){
      rename_win(XOOPS_ROOT_PATH."/uploads/tadnews/file/{$col_sn}_{$description}",XOOPS_ROOT_PATH."/uploads/tadnews/file/$new_file_name");
    }else{
      rename_win(XOOPS_ROOT_PATH."/uploads/tadnews/file/{$col_sn}_{$description}",XOOPS_ROOT_PATH."/uploads/tadnews/image/$new_file_name");
    }

    //更新檔名
    $sql1="update ".$xoopsDB->prefix("tadnews_files_center")." set `file_name`='{$new_file_name}',kind='{$kind}' where files_sn='{$files_sn}'";

    $xoopsDB->queryF($sql1);
  }


}


//新增必讀群組欄位
function chk_chk11(){
  global $xoopsDB;
  $sql="select count(`have_read_group`) from ".$xoopsDB->prefix("tad_news");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update11(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_news")." ADD `have_read_group` varchar(255) NOT NULL default ''";
  $xoopsDB->queryF($sql);
}




//新增簽收表格
function chk_chk12(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_news_sign");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update12(){
  global $xoopsDB;
  $sql="CREATE TABLE ".$xoopsDB->prefix("tad_news_sign")." (
  `sign_sn` MEDIUMINT UNSIGNED NOT NULL auto_increment,
  `nsn` SMALLINT UNSIGNED NOT NULL ,
  `uid` SMALLINT UNSIGNED NOT NULL ,
  `sign_time` DATETIME NOT NULL,
  PRIMARY KEY  (`sign_sn`)
  )";
  $xoopsDB->queryF($sql);
}


//新增排序欄位
function chk_chk13(){
  global $xoopsDB;
  $sql="select count(`page_sort`) from ".$xoopsDB->prefix("tad_news");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update13(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_news")." ADD `page_sort` SMALLINT(5) UNSIGNED NOT NULL default '0'";
  $xoopsDB->queryF($sql);
}




//新增郵件寄發表格
function chk_chk14(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_news_paper_send_log");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update14(){
  global $xoopsDB;
  $sql="CREATE TABLE ".$xoopsDB->prefix("tad_news_paper_send_log")." (
  `npsn` SMALLINT UNSIGNED NOT NULL ,
  `email` varchar(255) NOT NULL default '' ,
  `send_time` DATETIME NOT NULL,
  `log`  varchar(255) NOT NULL default '' ,
  PRIMARY KEY  (`npsn`,`email`)
  )";
  $xoopsDB->queryF($sql);
}



//新增標籤表格
function chk_chk15(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tad_news_tags");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update15(){
  global $xoopsDB;

  $sql="CREATE TABLE ".$xoopsDB->prefix("tad_news_tags")." (
  `tag_sn` smallint(5) UNSIGNED NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL default '',
  `color` varchar(255) NOT NULL default '',
  `enable` enum('0','1') NOT NULL,
  PRIMARY KEY  (`tag_sn`)
  )";
  $xoopsDB->queryF($sql);

  $sql="select distinct prefix_tag from ".$xoopsDB->prefix("tad_news")." where `prefix_tag`!=''";
  $result=$xoopsDB->query($sql);
  while(list($prefix_tag)=$xoopsDB->fetchRow($result)){
    $arr="";
    preg_match_all("/color[\s]*=[\s]*'([#a-zA-Z0-9]+)'[\s]*>\[(.*)\]/",$prefix_tag,$arr);
    $color=$arr[1][0];
    $tag=$arr[2][0];

    $sql="insert into ".$xoopsDB->prefix("tad_news_tags")." (`tag` , `color` , `enable`) values('{$tag}' , '{$color}' , '1')";
    $xoopsDB->queryF($sql);
    $tag_sn=$xoopsDB->getInsertId();

    if(!get_magic_quotes_runtime()){
      $prefix_tag=addslashes($prefix_tag);
    }
    $sql="update ".$xoopsDB->prefix("tad_news")." set `prefix_tag`='$tag_sn' where `prefix_tag`='{$prefix_tag}'";
    $xoopsDB->queryF($sql) or die($sql);
  }

}




//新增評分表格
function chk_chk16(){
  global $xoopsDB;
  $sql="select count(*) from ".$xoopsDB->prefix("tadnews_rank");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update16(){
  global $xoopsDB;
  $sql="CREATE TABLE ".$xoopsDB->prefix("tadnews_rank")." (
  `col_name` varchar(255) NOT NULL,
  `col_sn` smallint(5) unsigned NOT NULL,
  `rank` tinyint(3) unsigned NOT NULL,
  `uid` smallint(5) unsigned NOT NULL,
  `rank_date` datetime NOT NULL,
  PRIMARY KEY (`col_name`,`col_sn`,`uid`)
  )";
  $xoopsDB->queryF($sql);
}


//新增電子報副標題欄位
function chk_chk17(){
  global $xoopsDB;
  $sql="select count(`np_title`) from ".$xoopsDB->prefix("tad_news_paper");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}

function go_update17(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_news_paper")." ADD `np_title` varchar(255)  NOT NULL default ''";
  $xoopsDB->queryF($sql);
}


//新增original_filename欄位
function chk_chk18(){
  global $xoopsDB;
  $sql="select count(`original_filename`) from ".$xoopsDB->prefix("tadnews_files_center");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}


function go_update18(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tadnews_files_center")."
  ADD `original_filename` varchar(255) NOT NULL default '',
  ADD `hash_filename` varchar(255) NOT NULL default '',
  ADD `sub_dir` varchar(255) NOT NULL default ''";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());

  $sql="update ".$xoopsDB->prefix("tadnews_files_center")." set
  `original_filename`=`description`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
}


//新增 font_color 欄位
function chk_chk19(){
  global $xoopsDB;
  $sql="select count(`font_color`) from ".$xoopsDB->prefix("tad_news_tags");
  $result=$xoopsDB->query($sql);
  if(empty($result)) return false;
  return true;
}


function go_update19(){
  global $xoopsDB;
  $sql="ALTER TABLE ".$xoopsDB->prefix("tad_news_tags")."
  ADD `font_color` varchar(255) NOT NULL default '' after `tag`";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());

  $sql="update ".$xoopsDB->prefix("tad_news_tags")." set
  `font_color`='#ffffff'";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL."/modules/system/admin.php?fct=modulesadmin",30,  mysql_error());
}


//修正col_sn欄位
function chk_files_center(){
  global $xoopsDB;
  $sql="SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = '".$xoopsDB->prefix("tadnews_files_center")."' AND COLUMN_NAME = 'col_sn'";
  $result=$xoopsDB->query($sql);
  list($type)=$xoopsDB->fetchRow($result);
  if($type=='smallint')return true;
  return false;
}

//執行更新
function go_update_files_center(){
  global $xoopsDB;
  $sql="ALTER TABLE `".$xoopsDB->prefix("tadnews_files_center")."` CHANGE `col_sn` `col_sn` mediumint(9) unsigned NOT NULL default 0";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
  return true;
}

//修正uid欄位
function chk_uid(){
  global $xoopsDB;
  $sql="SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
  WHERE table_name = '".$xoopsDB->prefix("tad_news")."' AND COLUMN_NAME = 'uid'";
  $result=$xoopsDB->query($sql);
  list($type)=$xoopsDB->fetchRow($result);
  if($type=='smallint')return true;
  return false;
}

//執行更新
function go_update_uid(){
  global $xoopsDB;
  $sql="ALTER TABLE `".$xoopsDB->prefix("tad_news")."` CHANGE `uid` `uid` mediumint(8) unsigned NOT NULL default 0";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
  $sql="ALTER TABLE `".$xoopsDB->prefix("tad_news_sign")."` CHANGE `uid` `uid` mediumint(8) unsigned NOT NULL default 0";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
  $sql="ALTER TABLE `".$xoopsDB->prefix("tadnews_rank")."` CHANGE `uid` `uid` mediumint(8) unsigned NOT NULL default 0";
  $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL,3,  mysql_error());
  return true;
}

//建立目錄
function mk_dir($dir=""){
    //若無目錄名稱秀出警告訊息
    if(empty($dir))return;
    //若目錄不存在的話建立目錄
    if (!is_dir($dir)) {
        umask(000);
        //若建立失敗秀出警告訊息
        mkdir($dir, 0777);
    }
}

//拷貝目錄
function full_copy( $source="", $target=""){
  if ( is_dir( $source ) ){
    @mkdir( $target );
    $d = dir( $source );
    while ( FALSE !== ( $entry = $d->read() ) ){
      if ( $entry == '.' || $entry == '..' ){
        continue;
      }

      $Entry = $source . '/' . $entry;
      if ( is_dir( $Entry ) ) {
        full_copy( $Entry, $target . '/' . $entry );
        continue;
      }
      copy( $Entry, $target . '/' . $entry );
    }
    $d->close();
  }else{
    copy( $source, $target );
  }
}


function rename_win($oldfile,$newfile) {
   if (!rename($oldfile,$newfile)) {
      if (copy ($oldfile,$newfile)) {
         unlink($oldfile);
         return TRUE;
      }
      return FALSE;
   }
   return TRUE;
}

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

?>
