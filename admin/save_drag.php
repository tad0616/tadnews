<?php
/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";

$of_ncsn = (int)$_POST['of_ncsn'];
$ncsn    = (int)$_POST['ncsn'];

if (chk_cate_path($ncsn, $of_ncsn)) {
    die('不可移至自己的子目錄下');
}

$sql = "update " . $xoopsDB->prefix("tad_news_cate") . " set `of_ncsn`='{$of_ncsn}' where `ncsn`='{$ncsn}'";
$xoopsDB->queryF($sql) or die("Reset Fail! (" . date("Y-m-d H:i:s") . ")");

echo _MA_TREETABLE_MOVE_OK . " (" . date("Y-m-d H:i:s") . ")";

//檢查目的地編號是否在其子目錄下
function chk_cate_path($ncsn, $to_ncsn)
{
    global $xoopsDB;
    //抓出子目錄的編號
    $sql    = "select ncsn from " . $xoopsDB->prefix("tad_news_cate") . " where of_ncsn='{$ncsn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($sub_ncsn) = $xoopsDB->fetchRow($result)) {
        if (chk_cate_path($sub_ncsn, $to_ncsn)) {
            return true;
        }

        if ($sub_ncsn == $to_ncsn) {
            return true;
        }

    }
    return false;
}
