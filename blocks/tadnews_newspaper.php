<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}


//區塊主函式 (訂閱 / 取消電子報)
function tadnews_newspaper($options)
{
    global $xoopsDB, $xoopsUser;

    $sql = 'SELECT count(*) FROM ' . $xoopsDB->prefix('tad_news_paper_email') . '';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($counter) = $xoopsDB->fetchRow($result);

    //找出現有設定組
    $sql = 'SELECT nps_sn,title FROM ' . $xoopsDB->prefix('tad_news_paper_setup') . " WHERE status='1'";
    $result = $xoopsDB->query($sql);
    $i = 0;
    $option = [];
    while (list($nps_sn, $title) = $xoopsDB->fetchRow($result)) {
        $option[$i]['value'] = $nps_sn;
        $option[$i]['text'] = $title;
        $i++;
    }
    if (empty($option)) {
        return;
    }

    $block['counter'] = sprintf(_MB_TADNEWS_ORDER_COUNT, $counter);
    $block['option'] = $option;
    $block['email'] = $xoopsUser ? $xoopsUser->email() : '';

    return $block;
}
