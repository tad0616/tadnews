<?php
use Xmf\Request;
use XoopsModules\Tadnews\Page;

require_once __DIR__ . '/header.php';
// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

$op  = Request::getString('op');
$nsn = Request::getInt('nsn');

if ('sort_tabs' === $op) {
    Page::save_tabs_sort($nsn, Request::getVar('sort', [], null, 'array', 4));
} else {
    Page::save_page_sort(Request::getVar('tr', [], null, 'array', 4));
}
echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
