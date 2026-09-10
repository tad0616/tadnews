<?php
use XoopsModules\Tadnews\Cate;

/*-----------引入檔案區--------------*/
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
if (!class_exists('XoopsModules\Tadnews\Cate')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

Cate::save_cate_sort((int) $_POST['ncsn'], (int) $_POST['sort']);

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
