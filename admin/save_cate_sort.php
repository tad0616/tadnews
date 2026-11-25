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

//只接受 POST 並驗證 token（token 由 Cate::cate_tree() 補在 AJAX 請求中；不清除，讓同一頁可以連續拖曳）
if ('POST' !== \Xmf\Request::getMethod() || !$GLOBALS['xoopsSecurity']->check(false)) {
    die('Token Validation Error');
}

Cate::save_cate_sort((int) $_POST['ncsn'], (int) $_POST['sort']);

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
