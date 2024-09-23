<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_my_news.tpl';
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$kind = Request::getString('kind');
$ncsn = Request::getInt('ncsn');
$nsn = Request::getInt('nsn');
$fsn = Request::getInt('fsn');
$uid = Request::getInt('uid');
$files_sn = Request::getInt('files_sn');
$tag_sn = Request::getInt('tag_sn');

switch ($op) {
    //下載檔案
    case 'tufdl':
        $TadUpFiles = new TadUpFiles('tadnews');
        $TadUpFiles->add_file_counter($files_sn, false);
        exit;

    //刪除資料
    case 'delete_tad_news':
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //已經閱讀
    case 'have_read':
        have_read($nsn, $uid);
        header('location: ' . $_SERVER['PHP_SELF'] . "?nsn=$nsn");
        exit;

    default:
        list_tad_my_news();
        break;
}

/*-----------秀出結果區--------------*/

$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadnews/css/module.css');
if ($xoopsModuleConfig['use_table_shadow']) {
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/tadnews/css/module2.css');
}
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//列出某人所有新聞
function list_tad_my_news()
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $xoopsUser, $Tadnews;
    if (!$xoopsUser) {
        redirect_header('index.php', 3, _TAD_PERMISSION_DENIED);
    }
    $uid = $xoopsUser->uid();
    $Tadnews->set_show_enable(0);
    $Tadnews->set_view_uid($uid);
    $Tadnews->set_news_kind($kind);
    $Tadnews->set_summary(0);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_admin_tool(true);
    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);

    if (!empty($the_ncsn)) {
        $Tadnews->set_view_ncsn($the_ncsn);
    }
    $Tadnews->get_news();

    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
}
