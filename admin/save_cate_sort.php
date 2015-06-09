<?php
/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";

$ncsn = intval($_POST['ncsn']);
$sort = intval($_POST['sort']);
$sql  = "update " . $xoopsDB->prefix("tad_news_cate") . " set `sort`='{$sort}' where ncsn='{$ncsn}'";
$xoopsDB->queryF($sql) or die("Save Sort Fail! (" . date("Y-m-d H:i:s") . ")");

echo "Save Sort OK! (" . date("Y-m-d H:i:s") . ") ";
