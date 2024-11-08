<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once __DIR__ . '/header.php';
// 關閉除錯訊息
$xoopsLogger->activated = false;

$op = Request::getString('op');
$nsn = Request::getInt('nsn');

if ('sort_tabs' === $op) {
    $updateRecordsArray = Request::getVar('sort', [], null, 'array', 4);
    $sort = 1;
    foreach ($updateRecordsArray as $data_sort) {
        $data_sort = (int) $data_sort;
        $sql = 'UPDATE `' . $xoopsDB->prefix('tadnews_data_center') . '`
        SET `data_sort` = ?
        WHERE `col_name` = ?
        AND `col_sn` = ?
        AND `data_sort` = ?
        AND (`data_name` = ? OR `data_name` = ?)';

        $params = ["{$sort}000", 'nsn', $nsn, $data_sort, 'tab_title', 'tab_content'];
        Utility::query($sql, 'ssiiss', $params) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

        $sort++;
    }

    $sql = 'UPDATE `' . $xoopsDB->prefix('tadnews_data_center') . '` SET `data_sort`=`data_sort`/1000 WHERE `col_name`=? AND `col_sn`=? AND (`data_name`=? OR `data_name`=?)';
    Utility::query($sql, 'siss', ['nsn', $nsn, 'tab_title', 'tab_content']) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

    $sql = 'SELECT `data_name`, `data_value` FROM `' . $xoopsDB->prefix('tadnews_data_center') . '` WHERE `col_name`=? AND `col_sn`=? AND (`data_name`=? OR `data_name`=?) ORDER BY `data_sort`';
    $result = Utility::query($sql, 'siss', ['nsn', $nsn, 'tab_title', 'tab_content']) or Utility::web_error($sql, __FILE__, __LINE__);

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
        <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadnews/css/easy-responsive-tabs.css' type='text/css'>
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

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `news_content`=? WHERE `nsn`=?';
    Utility::query($sql, 'si', [$tabs_content, $nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

} else {
    $updateRecordsArray = Request::getVar('tr', [], null, 'array', 4);

    $sort = 1;
    foreach ($updateRecordsArray as $nsn) {
        $nsn = (int) $nsn;
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `page_sort`=? WHERE `nsn`=?';
        Utility::query($sql, 'ii', [$sort, $nsn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

        $sort++;
    }
}

echo _TAD_SORTED . "(" . date("Y-m-d H:i:s") . ")";
