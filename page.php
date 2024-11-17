<?php
use Xmf\Request;
use XoopsModules\Tadnews\Tools;
use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_page.tpl';
require_once __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$nsn = Request::getInt('nsn');
$fsn = Request::getInt('fsn');
$files_sn = Request::getInt('files_sn');

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

            $sql = 'SELECT `news_title` FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `nsn`=?';
            $result = Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

            list($news_title) = $xoopsDB->fetchRow($result);

            $op = 'show_page';
        } else {
            list_tad_all_pages($ncsn);
            $op = 'list_tad_all_pages';
        }

        break;
}

/*-----------秀出結果區--------------*/
$categoryHelper = new CategoryHelper('tad_news_cate', 'ncsn', 'of_ncsn', 'nc_title');
$arr = $categoryHelper->getCategoryPath($ncsn);

$path = Utility::tad_breadcrumb($ncsn, $arr, 'page.php', 'ncsn', 'nc_title', $news_title);

$xoopsTpl->assign('breadcrumb', $path);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('tadnews_adm', $tadnews_adm);
$xoTheme->addStylesheet('modules/tadnews/css/module.css');
if ($xoopsModuleConfig['use_table_shadow']) {
    $xoTheme->addStylesheet('modules/tadnews/css/module2.css');
}
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//顯示單一新聞
function show_page($nsn = '')
{
    global $Tadnews;

    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_news_kind('page');
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    $Tadnews->get_news();

    BootstrapTable::render();
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
    $moduleHandler = xoops_getHandler('module');
    $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql = 'SELECT `link_cate_sn` FROM `' . $xoopsDB->prefix('tad_themes_menu') . '` WHERE `link_cate_name`=?';
        $result = Utility::query($sql, 's', ['tadnews_page_cate']) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($link_cate_sn) = $xoopsDB->fetchRow($result)) {
            $link_cate_sn_arr[] = $link_cate_sn;
        }
    }
    $xoopsTpl->assign('link_cate_sn_arr', $link_cate_sn_arr);
    $xoopsTpl->assign('ok_cat', Tools::chk_user_cate_power('post'));
}

function add_to_menu($ncsn = '')
{
    global $xoopsDB;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `ncsn`=?';
    $result = Utility::query($sql, 'i', [$ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $cate = $xoopsDB->fetchArray($result);

    $moduleHandler = xoops_getHandler('module');
    $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_themes_menu') . '` (`of_level`, `position`, `itemname`, `itemurl`, `status`, `target`, `icon`, `link_cate_name`, `link_cate_sn`, `read_group`) VALUES (0, 1, ?, ?, 1, "_self", "", "tadnews_page_cate", ?, "")';
        Utility::query($sql, 'ssi', [$cate['nc_title'], XOOPS_URL . '/modules/tadnews/page.php?ncsn=' . $ncsn, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $xoopsDB->getInsertId();
    }
}

//頁籤排序
function tabs_sort($ncsn, $nsn)
{
    global $xoopsDB, $xoopsTpl, $Tadnews;

    $sql = 'SELECT `data_value`, `data_sort` FROM `' . $xoopsDB->prefix('tadnews_data_center') . '` WHERE `col_name` = \'nsn\' AND `col_sn` =? AND `data_name` = \'tab_title\' ORDER BY `data_sort`';
    $result = Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
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
