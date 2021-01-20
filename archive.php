<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tadnews_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/

//列出月份
function month_list($now_date = '')
{
    global $xoopsDB, $xoopsTpl;

    $sql = 'SELECT left(start_day,7) , count(*) FROM ' . $xoopsDB->prefix('tad_news') . " WHERE enable='1' GROUP BY left(start_day,7) ORDER BY start_day DESC";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 1;
    while (list($ym, $count) = $xoopsDB->fetchRow($result)) {
        $opt[$i]['value'] = $ym;
        $opt[$i]['count'] = $count;
        $opt[$i]['text'] = str_replace('-', '' . _MD_TADNEWS_YEAR, $ym) . _MD_TADNEWS_MONTH;
        $opt[$i]['selected'] = $now_date == $ym ? 'selected' : '';
        $i++;
    }

    Utility::get_jquery();
    $xoopsTpl->assign('opt', $opt);
}

//分月新聞
function archive($date = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    if (empty($date)) {
        $date = date('Y-m');
    }

    //$Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode('list');
    $Tadnews->set_show_month($date);
    $Tadnews->set_show_enable(1);
    $Tadnews->get_news();
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
    $date_title = Utility::to_utf8(str_replace('-', '' . _MD_TADNEWS_YEAR . ' ', $date) . _MD_TADNEWS_MONTH . _MD_TADNEWS_NEWS_TITLE);
    $xoopsTpl->assign('date_title', $date_title);
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$files_sn = Request::getInt('files_sn');
$date = Request::getString('date', date('Y-m'));
$date = mb_substr($date, 0, 7);

switch ($op) {
    //下載檔案
    case 'tufdl':
        $TadUpFiles = new TadUpFiles('tadnews');

        $TadUpFiles->add_file_counter($files_sn, false);
        exit;

    default:
        month_list($date);
        archive($date);
        $op = "archive";
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadnews/css/module.css');
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
