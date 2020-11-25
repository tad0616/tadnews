<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';

$op = Request::getString('op');
$nsn = Request::getInt('nsn');

if ('sort_tabs' === $op) {
    $updateRecordsArray = $_POST['sort'];
    $sort = 1;
    foreach ($updateRecordsArray as $data_sort) {
        $data_sort = (int) $data_sort;
        $sql = 'update ' . $xoopsDB->prefix('tadnews_data_center') . " set `data_sort`='{$sort}000' where `col_name`='nsn' and `col_sn`= '{$nsn}' and `data_sort`='{$data_sort}' and (`data_name`='tab_title' or `data_name`='tab_content')";
        $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }

    $sql = 'update ' . $xoopsDB->prefix('tadnews_data_center') . " set `data_sort`=`data_sort`/1000 where `col_name`='nsn' and `col_sn`= '{$nsn}' and (`data_name`='tab_title' or `data_name`='tab_content')";
    $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');

    $sql = 'select `data_name`,`data_value` from ' . $xoopsDB->prefix('tadnews_data_center') . " where `col_name`='nsn' and `col_sn`= '{$nsn}' and (`data_name`='tab_title' or `data_name`='tab_content') order by `data_sort`";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    $tab_title_div = $tab_content_div = '';
    while (list($data_name, $data_value) = $xoopsDB->fetchRow($result)) {
        if ('tab_title' === $data_name) {
            $tab_title_div .= "<li>$data_value</li>";
        } else {
            $tab_content_div .= "
        <div>
            {$data_value}
        </div>";
        }
    }

    $tabs_content = "
        <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css' type='text/css'>
        <script src='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js' type='text/javascript'></script>
        <div id='PageTab'>
        <ul class='resp-tabs-list vert'>
            {$tab_title_div}
        </ul>
        <div class='resp-tabs-container vert'>
            {$tab_content_div}
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function(){
            $('#PageTab').easyResponsiveTabs({
                tabidentify: 'vert',
                type: 'default', //Types: default, vertical, accordion
                width: 'auto',
                fit: true,
                closed: false
            });
        });
    </script>
    ";

    $tabs_content = $myts->addSlashes($tabs_content);
    $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set news_content = '{$tabs_content}' where nsn='$nsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
} else {
    $updateRecordsArray = $_POST['tr'];

    $sort = 1;
    foreach ($updateRecordsArray as $nsn) {
        $nsn = (int) $nsn;
        $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set `page_sort`='{$sort}' where `nsn`='{$nsn}'";
        $xoopsDB->queryF($sql) or die('Save Sort Fail! (' . date('Y-m-d H:i:s') . ')');
        $sort++;
    }
}

echo 'Save Sort OK! (' . date('Y-m-d H:i:s') . ')';
