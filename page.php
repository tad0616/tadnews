<?php
use Xmf\Request;
use XoopsModules\Tadnews\Cate;
use XoopsModules\Tadnews\Page;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_page.tpl';
require_once __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op       = Request::getString('op');
$ncsn     = Request::getInt('ncsn');
$nsn      = Request::getInt('nsn');
$fsn      = Request::getInt('fsn');
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
        Utility::xoops_security_check();
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //新增至選單
    case 'add_to_menu':
        Page::add_to_menu($ncsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'modify_page_cate':
        Cate::tad_news_cate_form($ncsn, 1);
        break;
    //更新資料
    case 'update_tad_news_cate':
        Cate::update_tad_news_cate($ncsn);
        header("location: {$_SERVER['PHP_SELF']}?ncsn=$ncsn");
        exit;

    case 'tabs_sort':
        Page::tabs_sort($ncsn, $nsn);
        break;

    default:
        if (!empty($nsn)) {
            Page::show_page($nsn);
            $news_title = Page::get_title($nsn);

            $op = 'show_page';
        } else {
            Page::list_tad_all_pages($ncsn);
            $op = 'list_tad_all_pages';
        }

        break;
}

/*-----------秀出結果區--------------*/
$categoryHelper = new CategoryHelper('tad_news_cate', 'ncsn', 'of_ncsn', 'nc_title');
$arr            = $categoryHelper->getCategoryPath($ncsn, 'tad_news');

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
