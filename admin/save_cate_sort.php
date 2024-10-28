<?php
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
// 關閉除錯訊息
$xoopsLogger->activated = false;
$ncsn = (int) $_POST['ncsn'];
$sort = (int) $_POST['sort'];
$sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `sort`=? WHERE `ncsn`=?';
Utility::query($sql, 'ii', [$sort, $ncsn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
