<?php
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_my_news.tpl';
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//列出某人所有新聞
function list_tad_my_news()
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $xoopsUser, $Tadnews;

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

    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}

/*-----------執行動作判斷區----------*/
$op = (empty($_REQUEST['op'])) ? '' : $_REQUEST['op'];

$nsn = (isset($_REQUEST['nsn'])) ? (int) $_REQUEST['nsn'] : 0;
$ncsn = (isset($_REQUEST['ncsn'])) ? (int) $_REQUEST['ncsn'] : 0;
$fsn = (isset($_REQUEST['fsn'])) ? (int) $_REQUEST['fsn'] : 0;
$uid = (isset($_REQUEST['uid'])) ? (int) $_REQUEST['uid'] : '';
$kind = (empty($_REQUEST['kind'])) ? '' : $_REQUEST['kind'];
$tag_sn = (isset($_REQUEST['tag_sn'])) ? (int) $_REQUEST['tag_sn'] : '';

switch ($op) {
    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles = new TadUpFiles('tadnews');
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;
        break;
    //刪除資料
    case 'delete_tad_news':
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;
        break;
    //已經閱讀
    case 'have_read':
        have_read($nsn, $uid);
        header('location: ' . $_SERVER['PHP_SELF'] . "?nsn=$nsn");
        exit;
        break;
    default:
        list_tad_my_news();
        break;
}

/*-----------秀出結果區--------------*/
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
