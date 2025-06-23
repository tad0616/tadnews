<?php
use XoopsModules\Tadtools\Utility;

require_once 'header.php';

add_tad_news_cate($_POST['value']);

//新增資料到tad_news_cate中
function add_tad_news_cate($title = '', $no_news = '0')
{
    global $xoopsDB, $xoopsModuleConfig;
    //安全判斷
    if ($_SERVER['SERVER_ADDR'] != '127.0.0.1' && !$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', (Array) $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
    }
    $enable_group = $enable_post_group = $setup = '';
    $sql          = 'SELECT MAX(`sort`) FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn`=?';
    $result       = Utility::query($sql, 's', ['']) or Utility::web_error($sql, __FILE__, __LINE__);

    list($sort) = $xoopsDB->fetchRow($result);
    $sort++;

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_cate') . '` (`of_ncsn`, `nc_title`, `enable_group`, `enable_post_group`, `sort`, `not_news`, `setup`) VALUES (?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'isssiss', [0, $title, $enable_group, $enable_post_group, $sort, $no_news, $setup]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADNEWS_DB_ADD_ERROR1);

    //取得最後新增資料的流水編號
    $ncsn = $xoopsDB->getInsertId();

    return $ncsn;
}

echo $_POST['value'];
