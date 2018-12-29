<?php

function xoops_module_update_tadnews(&$module, $old_version)
{
    global $xoopsDB;

    if (!chk_chk9()) {
        go_update9();
    }

    if (!chk_chk10()) {
        go_update10();
    }

    if (!chk_chk11()) {
        go_update11();
    }

    if (!chk_chk12()) {
        go_update12();
    }

    if (!chk_chk13()) {
        go_update13();
    }

    if (!chk_chk14()) {
        go_update14();
    }

    if (!chk_chk15()) {
        go_update15();
    }

    if (!chk_chk16()) {
        go_update16();
    }

    if (!chk_chk17()) {
        go_update17();
    }

    if (!chk_chk18()) {
        go_update18();
    }

    if (!chk_chk19()) {
        go_update19();
    }

    if (chk_chk20()) {
        go_update20();
    }
    if (chk_chk21()) {
        go_update21();
    }

    if (chk_chk22()) {
        go_update22();
    }

    //調整檔案上傳欄位col_sn為mediumint(9)格式
    if (chk_files_center()) {
        go_update_files_center();
    }

    if (chk_uid()) {
        go_update_uid();
    }

    $old_fckeditor = XOOPS_ROOT_PATH . "/modules/tadnews/fckeditor";
    if (is_dir($old_fckeditor)) {
        delete_directory($old_fckeditor);
        delete_directory(XOOPS_ROOT_PATH . "/modules/tadnews/dhtmlgoodies_calendar");
    }
    chk_tadnews_block();

    //新增檔案欄位
    if (chk_fc_tag()) {
        go_fc_tag();
    }

    //調整檔案上傳欄位col_id的預設值
    if (chk_data_center_col_id()) {
        go_update_data_center_col_id();
    }

    return true;
}

//新增檔案欄位
function chk_fc_tag()
{
    global $xoopsDB;
    $sql    = "SELECT count(`tag`) FROM " . $xoopsDB->prefix("tadnews_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_fc_tag()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tadnews_files_center") . "
    ADD `upload_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上傳時間',
    ADD `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上傳者',
    ADD `tag` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '註記'
    ";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 30, $xoopsDB->error());
}

//刪除錯誤的重複欄位及樣板檔
function chk_tadnews_block()
{
    global $xoopsDB;
    //die(var_export($xoopsConfig));
    include XOOPS_ROOT_PATH . '/modules/tadnews/xoops_version.php';

    //先找出該有的區塊以及對應樣板
    foreach ($modversion['blocks'] as $i => $block) {
        $show_func                = $block['show_func'];
        $tpl_file_arr[$show_func] = $block['template'];
        $tpl_desc_arr[$show_func] = $block['description'];
    }

    //找出目前所有的樣板檔
    $sql    = "SELECT bid,name,visible,show_func,template FROM `" . $xoopsDB->prefix("newblocks") . "` WHERE `dirname` = 'tadnews' ORDER BY `func_num`";
    $result = $xoopsDB->query($sql);
    while (list($bid, $name, $visible, $show_func, $template) = $xoopsDB->fetchRow($result)) {
        //假如現有的區塊和樣板對不上就刪掉
        if ($template != $tpl_file_arr[$show_func]) {
            $sql = "delete from " . $xoopsDB->prefix("newblocks") . " where bid='{$bid}'";
            $xoopsDB->queryF($sql);

            //連同樣板以及樣板實體檔案也要刪掉
            $sql = "delete from " . $xoopsDB->prefix("tplfile") . " as a left join " . $xoopsDB->prefix("tplsource") . "  as b on a.tpl_id=b.tpl_id where a.tpl_refid='$bid' and a.tpl_module='tadnews' and a.tpl_type='block'";
            $xoopsDB->queryF($sql);
        } else {
            $sql = "update " . $xoopsDB->prefix("tplfile") . " set tpl_file='{$template}' , tpl_desc='{$tpl_desc_arr[$show_func]}' where tpl_refid='{$bid}'";
            $xoopsDB->queryF($sql);
        }
    }
}

//新增置頂日期欄位
function chk_chk9()
{
    global $xoopsDB;
    $sql    = "SELECT count(`always_top_date`) FROM " . $xoopsDB->prefix("tad_news");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update9()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news") . " ADD `always_top_date` DATETIME NOT NULL";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, show_error($sql));
}

//建立搬移檔案新表格
function chk_chk10()
{
    global $xoopsDB;
    $sql = "SELECT count(`col_name`) FROM " . $xoopsDB->prefix("tadnews_files_center");
    //$sql="SHOW FIELDS FROM ".$xoopsDB->prefix("tadnews_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update10()
{
    global $xoopsDB;
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/cate");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/file");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/image");
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/image/.thumbs");

    //建立電子報佈景
    mk_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/themes");
    if (is_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/themes/bluefreedom2")) {
        delete_directory(XOOPS_ROOT_PATH . "/uploads/tadnews/themes/bluefreedom2");
    }
    full_copy(XOOPS_ROOT_PATH . "/modules/tadnews/images/bluefreedom2", XOOPS_ROOT_PATH . "/uploads/tadnews/themes/bluefreedom2");

    //表格改名
    $sql = "RENAME TABLE " . $xoopsDB->prefix("tad_news_files") . "  TO " . $xoopsDB->prefix("tadnews_files_center");
    $xoopsDB->queryF($sql);

    //修改表格
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tadnews_files_center") . "`
      CHANGE `fsn` `files_sn` SMALLINT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT,
      CHANGE `nsn` `col_sn` SMALLINT( 5 ) UNSIGNED NOT NULL,
      ADD `col_name` VARCHAR( 255 ) NOT NULL AFTER `files_sn` ,
      ADD `sort` SMALLINT UNSIGNED NOT NULL AFTER `col_sn` ,
      ADD `kind` ENUM( 'img', 'file' ) NOT NULL AFTER `sort`,
      ADD `description` TEXT NOT NULL AFTER `file_type`";
    $xoopsDB->queryF($sql) or die($sql);

    //套入描述以及欄位名稱
    $sql = "update " . $xoopsDB->prefix("tadnews_files_center") . " set `col_name`='nsn', `description`=`file_name`";

    $xoopsDB->queryF($sql);

    $sql    = "SELECT files_sn,file_name,file_type,description,col_name,col_sn FROM " . $xoopsDB->prefix("tadnews_files_center") . "";
    $result = $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, show_error($sql));
    while (list($files_sn, $file_name, $file_type, $description, $col_name, $col_sn) = $xoopsDB->fetchRow($result)) {
        $kind          = (substr($file_type, 0, 5) == "image") ? "img" : "file";
        $new_file_name = "{$col_name}_{$col_sn}_{$files_sn}" . substr($description, -4);
        if ($kind == "file") {
            rename_win(XOOPS_ROOT_PATH . "/uploads/tadnews/file/{$col_sn}_{$description}", XOOPS_ROOT_PATH . "/uploads/tadnews/file/$new_file_name");
        } else {
            rename_win(XOOPS_ROOT_PATH . "/uploads/tadnews/file/{$col_sn}_{$description}", XOOPS_ROOT_PATH . "/uploads/tadnews/image/$new_file_name");
        }

        //更新檔名
        $sql1 = "update " . $xoopsDB->prefix("tadnews_files_center") . " set `file_name`='{$new_file_name}',kind='{$kind}' where files_sn='{$files_sn}'";

        $xoopsDB->queryF($sql1);
    }
}

//新增必讀群組欄位
function chk_chk11()
{
    global $xoopsDB;
    $sql    = "SELECT count(`have_read_group`) FROM " . $xoopsDB->prefix("tad_news");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update11()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news") . " ADD `have_read_group` VARCHAR(255) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql);
}

//新增簽收表格
function chk_chk12()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_news_sign");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update12()
{
    global $xoopsDB;
    $sql = "CREATE TABLE " . $xoopsDB->prefix("tad_news_sign") . " (
  `sign_sn` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nsn` SMALLINT UNSIGNED NOT NULL ,
  `uid` SMALLINT UNSIGNED NOT NULL ,
  `sign_time` DATETIME NOT NULL,
  PRIMARY KEY  (`sign_sn`)
  )";
    $xoopsDB->queryF($sql);
}

//新增排序欄位
function chk_chk13()
{
    global $xoopsDB;
    $sql    = "SELECT count(`page_sort`) FROM " . $xoopsDB->prefix("tad_news");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update13()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news") . " ADD `page_sort` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'";
    $xoopsDB->queryF($sql);
}

//新增郵件寄發表格
function chk_chk14()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_news_paper_send_log");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update14()
{
    global $xoopsDB;
    $sql = "CREATE TABLE " . $xoopsDB->prefix("tad_news_paper_send_log") . " (
    `npsn` SMALLINT UNSIGNED NOT NULL ,
    `email` VARCHAR(255) NOT NULL DEFAULT '' ,
    `send_time` DATETIME NOT NULL,
    `log`  VARCHAR(255) NOT NULL DEFAULT '' ,
    PRIMARY KEY  (`npsn`,`email`)
    )";
    $xoopsDB->queryF($sql);
}

//新增標籤表格
function chk_chk15()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tad_news_tags");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update15()
{
    global $xoopsDB;

    $sql = "CREATE TABLE " . $xoopsDB->prefix("tad_news_tags") . " (
  `tag_sn` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag` VARCHAR(255) NOT NULL DEFAULT '',
  `color` VARCHAR(255) NOT NULL DEFAULT '',
  `enable` ENUM('0','1') NOT NULL,
  PRIMARY KEY  (`tag_sn`)
  )";
    $xoopsDB->queryF($sql);

    $sql    = "SELECT DISTINCT prefix_tag FROM " . $xoopsDB->prefix("tad_news") . " WHERE `prefix_tag`!=''";
    $result = $xoopsDB->query($sql);
    while (list($prefix_tag) = $xoopsDB->fetchRow($result)) {
        $arr = "";
        preg_match_all("/color[\s]*=[\s]*'([#a-zA-Z0-9]+)'[\s]*>\[(.*)\]/", $prefix_tag, $arr);
        $color = $arr[1][0];
        $tag   = $arr[2][0];

        $sql = "insert into " . $xoopsDB->prefix("tad_news_tags") . " (`tag` , `color` , `enable`) values('{$tag}' , '{$color}' , '1')";
        $xoopsDB->queryF($sql);
        $tag_sn = $xoopsDB->getInsertId();

        if (!get_magic_quotes_runtime()) {
            $prefix_tag = addslashes($prefix_tag);
        }
        $sql = "update " . $xoopsDB->prefix("tad_news") . " set `prefix_tag`='$tag_sn' where `prefix_tag`='{$prefix_tag}'";
        $xoopsDB->queryF($sql) or die($sql);
    }
}

//新增評分表格
function chk_chk16()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tadnews_rank");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update16()
{
    global $xoopsDB;
    $sql = "CREATE TABLE " . $xoopsDB->prefix("tadnews_rank") . " (
  `col_name` VARCHAR(255) NOT NULL,
  `col_sn` SMALLINT(5) UNSIGNED NOT NULL,
  `rank` TINYINT(3) UNSIGNED NOT NULL,
  `uid` SMALLINT(5) UNSIGNED NOT NULL,
  `rank_date` DATETIME NOT NULL,
  PRIMARY KEY (`col_name`,`col_sn`,`uid`)
  )";
    $xoopsDB->queryF($sql);
}

//新增電子報副標題欄位
function chk_chk17()
{
    global $xoopsDB;
    $sql    = "SELECT count(`np_title`) FROM " . $xoopsDB->prefix("tad_news_paper");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update17()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news_paper") . " ADD `np_title` VARCHAR(255)  NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql);
}

//新增original_filename欄位
function chk_chk18()
{
    global $xoopsDB;
    $sql    = "SELECT count(`original_filename`) FROM " . $xoopsDB->prefix("tadnews_files_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update18()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tadnews_files_center") . "
  ADD `original_filename` VARCHAR(255) NOT NULL DEFAULT '',
  ADD `hash_filename` VARCHAR(255) NOT NULL DEFAULT '',
  ADD `sub_dir` VARCHAR(255) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());

    $sql = "update " . $xoopsDB->prefix("tadnews_files_center") . " set
  `original_filename`=`description`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());
}

//新增 font_color 欄位
function chk_chk19()
{
    global $xoopsDB;
    $sql    = "SELECT count(`font_color`) FROM " . $xoopsDB->prefix("tad_news_tags");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

function go_update19()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news_tags") . "
    ADD `font_color` VARCHAR(255) NOT NULL DEFAULT '' AFTER `tag`";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());

    $sql = "update " . $xoopsDB->prefix("tad_news_tags") . " set
    `font_color`='#ffffff'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());
}

//調大內容欄位為 longtext
function chk_chk20()
{
    global $xoopsDB;
    $sql    = "SHOW Fields FROM " . $xoopsDB->prefix("tad_news") . " where `Field`='news_content' and `Type`='text'";
    $result = $xoopsDB->queryF($sql) or die($sql);
    $all    = $xoopsDB->fetchRow($result);
    if ($all === false) {
        return false;
    }
    return true;
}

function go_update20()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("tad_news") . " CHANGE `news_content` `news_content` LONGTEXT NOT NULL";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());
    return true;
}

//移除封面圖的hash_filename
function chk_chk21()
{
    global $xoopsDB;
    $sql    = "SELECT hash_filename FROM " . $xoopsDB->prefix("tadnews_files_center") . " WHERE `col_name`='news_pic'";
    $result = $xoopsDB->query($sql) or die($sql);
    $all    = $xoopsDB->fetchRow($result);
    if ($all === false) {
        return false;
    }
    return true;
}

function go_update21()
{
    global $xoopsDB;
    $sql = "update " . $xoopsDB->prefix("tadnews_files_center") . " set hash_filename='' where `col_name`='news_pic'";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin", 3, $xoopsDB->error());
    return true;
}

//新增簽收表格
function chk_chk22()
{
    global $xoopsDB;
    $sql    = "SELECT count(*) FROM " . $xoopsDB->prefix("tadnews_data_center");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

function go_update22()
{
    global $xoopsDB;
    $sql = "CREATE TABLE " . $xoopsDB->prefix("tadnews_data_center") . " (
        `mid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT ,
        `col_name` varchar(100) NOT NULL DEFAULT '',
        `col_sn` mediumint(9) unsigned NOT NULL DEFAULT '0',
        `data_name` varchar(100) NOT NULL DEFAULT '',
        `data_value` text NOT NULL ,
        `data_sort` mediumint(9) unsigned NOT NULL DEFAULT '0',
        `col_id` varchar(100) NOT NULL,
        `update_time` datetime NOT NULL,
      PRIMARY KEY (`mid`,`col_name`,`col_sn`,`data_name`,`data_sort`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    $xoopsDB->queryF($sql);
}

//修正col_sn欄位
function chk_files_center()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = '" . $xoopsDB->prefix("tadnews_files_center") . "' AND COLUMN_NAME = 'col_sn'";
    $result     = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ($type == 'smallint') {
        return true;
    }

    return false;
}


//執行更新
function go_update_files_center()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tadnews_files_center") . "` CHANGE `col_sn` `col_sn` MEDIUMINT(9) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());
    return true;
}


//修正col_id欄位
function chk_data_center_col_id()
{
    global $xoopsDB;
    $sql = "SELECT Fields FROM `" . $xoopsDB->prefix("tadnews_data_center") . "` where `Field`='col_id' and `Default` IS NULL";
    $result     = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}

//執行更新
function go_update_data_center_col_id()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tadnews_data_center") . "` CHANGE `col_id` `col_id` varchar(100) NOT NULL DEFAULT ''";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());
    return true;
}


//修正uid欄位
function chk_uid()
{
    global $xoopsDB;
    $sql = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = '" . $xoopsDB->prefix("tad_news") . "' AND COLUMN_NAME = 'uid'";
    $result     = $xoopsDB->query($sql);
    list($type) = $xoopsDB->fetchRow($result);
    if ($type == 'smallint') {
        return true;
    }

    return false;
}

//執行更新
function go_update_uid()
{
    global $xoopsDB;
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_news") . "` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tad_news_sign") . "` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());
    $sql = "ALTER TABLE `" . $xoopsDB->prefix("tadnews_rank") . "` CHANGE `uid` `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT 0";
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, $xoopsDB->error());
    return true;
}

//建立目錄
if (!function_exists('mk_dir')) {
    function mk_dir($dir = "")
    {
        //若無目錄名稱秀出警告訊息
        if (empty($dir)) {
            return;
        }

        //若目錄不存在的話建立目錄
        if (!is_dir($dir)) {
            umask(000);
            //若建立失敗秀出警告訊息
            mkdir($dir, 0777);
        }
    }
}

//拷貝目錄
if (!function_exists('full_copy')) {
    function full_copy($source = "", $target = "")
    {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);
            while (false !== ($entry = $d->read())) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }
            $d->close();
        } else {
            copy($source, $target);
        }
    }
}

if (!function_exists('rename_win')) {
    function rename_win($oldfile, $newfile)
    {
        if (!rename($oldfile, $newfile)) {
            if (copy($oldfile, $newfile)) {
                unlink($oldfile);
                return true;
            }
            return false;
        }
        return true;
    }
}

if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
    {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
        }

        if (!$dir_handle) {
            return false;
        }

        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file)) {
                    unlink($dirname . "/" . $file);
                } else {
                    delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}
