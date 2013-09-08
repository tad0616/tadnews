<?php
include_once "admin_header.php";
$updateRecordsArray     = $_POST['tr'];

$sort = 1;
foreach ($updateRecordsArray as $recordIDValue) {
  $sql="update ".$xoopsDB->prefix("tad_news")." set `page_sort`='{$sort}' where `nsn`='{$recordIDValue}'";
  $xoopsDB->queryF($sql) or die("Save Sort Fail! (".date("Y-m-d H:i:s").")");
  $sort++;
}

echo "Save Sort OK! (".date("Y-m-d H:i:s").")";
?>