<?php
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadnews\Tadnews;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_page.tpl';
require_once __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/

//顯示單一新聞
function show_page($nsn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_news_kind('page');
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    $Tadnews->get_news();
}

//列出所有tad_news資料
function list_tad_all_pages($the_ncsn = 0)
{
    global $xoopsTpl, $xoopsDB, $Tadnews;

    Utility::get_jquery(true);
    $Tadnews->set_news_kind('page');
    $Tadnews->set_show_num('none');
    $Tadnews->set_view_ncsn($the_ncsn);
    $Tadnews->get_cate_news();

    $link_cate_sn_arr = [];
    $moduleHandler    = xoops_getHandler('module');
    $TadThemesModule  = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql    = 'select link_cate_sn from ' . $xoopsDB->prefix('tad_themes_menu') . " where `link_cate_name`='tadnews_page_cate'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($link_cate_sn) = $xoopsDB->fetchRow($result)) {
            $link_cate_sn_arr[] = $link_cate_sn;
        }
    }
    $xoopsTpl->assign('link_cate_sn_arr', $link_cate_sn_arr);
    $xoopsTpl->assign('ok_cat', $Tadnews->chk_user_cate_power('post'));
}

function add_to_menu($ncsn = '')
{
    global $xoopsDB;

    $sql    = 'select * from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='$ncsn'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $cate   = $xoopsDB->fetchArray($result);

    $moduleHandler   = xoops_getHandler('module');
    $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql = 'insert into ' . $xoopsDB->prefix('tad_themes_menu') . " (`of_level`,`position`,`itemname`,`itemurl`,`status`,`target`,`icon`, `link_cate_name` ,`link_cate_sn`, `read_group`) values('0','1','{$cate['nc_title']}','" . XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}','1','_self','', 'tadnews_page_cate','{$ncsn}', '')";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        //取得最後新增資料的流水編號
        $menuid = $xoopsDB->getInsertId();
    }
}

//頁籤排序
function tabs_sort($ncsn, $nsn)
{
    global $xoopsDB, $xoopsTpl, $Tadnews;

    $sql    = 'select `data_value`,`data_sort` from ' . $xoopsDB->prefix('tadnews_data_center') . " where `col_name`='nsn' and `col_sn`= '{$nsn}' and `data_name`='tab_title'  order by `data_sort`";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts    = \MyTextSanitizer::getInstance();
    $tab_div = [];
    while (list($data_value, $data_sort) = $xoopsDB->fetchRow($result)) {
        $tab_div[$data_sort] = $data_value;
    }
    $xoopsTpl->assign('ncsn', $ncsn);
    $xoopsTpl->assign('nsn', $nsn);
    $xoopsTpl->assign('tab_div', $tab_div);
    Utility::get_jquery(true);
    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_news_kind('page');
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    $Tadnews->get_news();
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn     = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn      = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$fsn      = system_CleanVars($_REQUEST, 'fsn', 0, 'int');
$files_sn = system_CleanVars($_REQUEST, 'files_sn', 0, 'int');

$news_title = '';

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

    //新增至選單
    case 'add_to_menu':
        add_to_menu($ncsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'modify_page_cate':
        tad_news_cate_form($ncsn, 1);
        break;
    //更新資料
    case 'update_tad_news_cate':
        update_tad_news_cate($ncsn);
        header("location: {$_SERVER['PHP_SELF']}?ncsn=$ncsn");
        exit;

    case 'tabs_sort':
        tabs_sort($ncsn, $nsn);
        break;

    default:
        if (!empty($nsn)) {
            show_page($nsn);

            $sql              = 'select news_title from ' . $xoopsDB->prefix('tad_news') . " where nsn='$nsn'";
            $result           = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
            list($news_title) = $xoopsDB->fetchRow($result);

            $op = 'show_page';
        } else {
            list_tad_all_pages($ncsn);
            $op = 'list_tad_all_pages';
        }

        break;
}

/*-----------秀出結果區--------------*/
// $arr = get_tadnews_cate_path($ncsn);
// die(var_dump($arr));

$arr  = get_tadnews_cate_path($ncsn);
$path = Utility::tad_breadcrumb($ncsn, $arr, 'page.php', 'ncsn', 'nc_title', $news_title);

$xoopsTpl->assign('breadcrumb', $path);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign('isAdmin', $isAdmin);
$xoopsTpl->assign('now_op', $op);
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
