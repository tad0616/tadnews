<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadnews_newspaper.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$npsn = Request::getInt('npsn');

switch ($op) {
    case 'preview':
        $main = preview_newspaper($npsn);
        break;
    default:
        $main = list_newspaper();
        break;
}

/*-----------秀出結果區--------------*/
if ('preview' === $op) {
    echo $main;
} else {
    $xoopsTpl->assign('xoops_showrblock', 0);
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
    require_once XOOPS_ROOT_PATH . '/footer.php';
}

/*-----------function區--------------*/

//列出newspaper資料
function list_newspaper()
{
    global $xoopsDB, $xoopsTpl;

    $myts = \MyTextSanitizer::getInstance();

    $sql = 'SELECT a.npsn,a.number,b.title,a.np_date FROM ' . $xoopsDB->prefix('tad_news_paper') . ' AS a ,' . $xoopsDB->prefix('tad_news_paper_setup') . " AS b WHERE a.nps_sn=b.nps_sn AND b.status='1' ORDER BY a.np_date DESC";

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 10, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $main = [];
    while (list($allnpsn, $number, $title, $np_date) = $xoopsDB->fetchRow($result)) {
        $title = $myts->htmlSpecialChars($title);
        $main[$i]['allnpsn'] = $allnpsn;
        $main[$i]['title'] = $title . sprintf(_MD_TADNEWS_NP_TITLE, $number);
        $main[$i]['np_date'] = $np_date;
        $i++;
    }

    $xoopsTpl->assign('page', $main);
    $xoopsTpl->assign('bar', $bar);
}
