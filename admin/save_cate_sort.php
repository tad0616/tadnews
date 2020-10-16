<?php
/*-----------引入檔案區--------------*/
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$ncsn = (int) $_POST['ncsn'];
$sort = (int) $_POST['sort'];
$sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . " set `sort`='{$sort}' where ncsn='{$ncsn}'";
$xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ') ';
