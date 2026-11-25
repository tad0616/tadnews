<?php
use Xmf\Request;
use XoopsModules\Tadnews\Page;
use XoopsModules\Tadnews\Tools;

require_once __DIR__ . '/header.php';
// 關閉除錯訊息
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;

//只接受 POST 並驗證 token；不清除 token，讓同一頁可以連續拖曳排序
if ('POST' !== Request::getMethod() || !$GLOBALS['xoopsSecurity']->check(false)) {
    die(_TAD_SORT_FAIL . ' (Token Validation Error)');
}
if (empty($xoopsUser)) {
    die(_TADNEWS_NO_ADMIN_POWER);
}

$op  = Request::getString('op');
$nsn = Request::getInt('nsn');

if ('sort_tabs' === $op) {
    //頁籤排序會重組並覆寫文章內容，只限管理員或作者本人
    $news = $Tadnews->get_tad_news($nsn);
    if (empty($news['nsn']) || (!$tadnews_adm && (int) $news['uid'] !== (int) $xoopsUser->uid())) {
        die(_TADNEWS_NO_ADMIN_POWER);
    }
    Page::save_tabs_sort($nsn, Request::getVar('sort', [], 'POST', 'array', 4));
} else {
    //單頁排序：每一篇都必須屬於目前使用者有發布權限的分類
    $nsn_arr = Request::getVar('tr', [], 'POST', 'array', 4);
    $ok_cat  = Tools::chk_user_cate_power('post');
    foreach ($nsn_arr as $sort_nsn) {
        $news = $Tadnews->get_tad_news((int) $sort_nsn);
        if (empty($news['nsn']) || (!$tadnews_adm && !in_array((int) $news['ncsn'], $ok_cat, true))) {
            die(_TADNEWS_NO_ADMIN_POWER);
        }
    }
    Page::save_page_sort($nsn_arr);
}
echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
