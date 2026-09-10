<?php
use Xmf\Request;
use XoopsModules\Tadnews\Sign;
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\StarRating;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tadnews_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op       = Request::getString('op');
$ncsn     = Request::getInt('ncsn');
$nsn      = Request::getInt('nsn');
$fsn      = Request::getInt('fsn');
$uid      = Request::getInt('uid');
$kind     = Request::getString('kind');
$tag_sn   = Request::getInt('tag_sn');
$show_uid = Request::getInt('show_uid');
$mod_name = Request::getString('mod_name');
$col_name = Request::getString('col_name');
$col_sn   = Request::getInt('col_sn');
$rank     = Request::getString('rank');
$files_sn = Request::getInt('files_sn');

switch ($op) {
    //下載檔案
    case 'tufdl':
        $TadUpFiles = new TadUpFiles('tadnews');
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;

    //刪除資料
    case 'delete_tad_news':
        Utility::xoops_security_check();
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //已經閱讀
    case 'have_read':
        Sign::have_read($nsn, $uid);
        header('location: ' . $_SERVER['PHP_SELF'] . "?nsn=$nsn");
        exit;

    //列出簽收狀況
    case 'list_sign':
        $op = 'list_sign';
        Sign::list_sign($nsn);
        $xoopsTpl->assign('op', $op);
        break;

    //列出某人狀況
    case 'list_user_sign':
        $op = 'list_user_sign';
        Sign::list_user_sign($uid);
        $xoopsTpl->assign('op', $op);
        break;

    case 'save_rating':
        StarRating::save_rating($mod_name, $col_name, $col_sn, $rank);
        break;
    default:

        $xoopsTpl->assign('show_rss', $xoopsModuleConfig['show_rss']);
        //把過期的置頂文徹下
        Tadnews::chk_always_top();
        if (!empty($nsn)) {
            $op = 'tadnews_show';
            show_news($nsn);
        } elseif (!empty($tag_sn)) {
            $op = 'tadnews_list';
            list_tad_tag_news($tag_sn);
        } elseif (!empty($ncsn)) {
            if ('summary' === $xoopsModuleConfig['cate_show_mode']) {
                $op = 'tadnews_summary';
                list_index_news($ncsn, '', true);
            } else {
                $op = 'tadnews_list';
                list_index_news($ncsn);
            }
        } else {
            if ('summary' === $xoopsModuleConfig['show_mode']) {
                $op = 'tadnews_summary';
                list_index_news(null, $show_uid, true);
            } elseif ('cate' === $xoopsModuleConfig['show_mode']) {
                $op = 'tadnews_cate';
                list_tad_cate_news($show_uid);
            } else {
                $op = 'tadnews_list';
                list_index_news(null, $show_uid);
            }
        }

        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('tadnews_adm', $tadnews_adm);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tadnews/css/module.css');
if ($xoopsModuleConfig['use_table_shadow']) {
    $xoTheme->addStylesheet('modules/tadnews/css/module2.css');
}
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

//指定分類時，assign 該分類的路徑導覽
function assign_news_path($the_ncsn = '')
{
    global $xoopsTpl;

    if (empty($the_ncsn)) {
        return;
    }
    $categoryHelper = new CategoryHelper('tad_news_cate', 'ncsn', 'of_ncsn', 'nc_title');
    $arr            = $categoryHelper->getCategoryPath($the_ncsn, 'tad_news');
    $xoopsTpl->assign('path', Utility::tad_breadcrumb($the_ncsn, $arr, 'index.php', 'ncsn', 'nc_title'));
}

//列出所有tad_news資料（$summary=true 為 summary 模式，多帶摘要與封面）
function list_index_news($the_ncsn = '', $show_uid = '', $summary = false)
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    if ($summary) {
        $Tadnews->set_summary('300');
        $Tadnews->set_cover(true, 'db');
    }
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    if ($the_ncsn > 0) {
        $Tadnews->set_view_ncsn($the_ncsn);
        $Tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
        $xoopsTpl->assign('cate', $Tadnews->get_tad_news_cate($the_ncsn));
    } else {
        $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    }

    $Tadnews->get_news();
    $xoopsTpl->assign('ncsn', $the_ncsn);
    assign_news_path($the_ncsn);
}

//列出所有tad_news資料
function list_tad_tag_news($tag_sn = '')
{
    global $xoopsModuleConfig, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_view_tag($tag_sn);

    $Tadnews->get_news();
}

//以分類為單位列出tad_news資料
function list_tad_cate_news($show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    $Tadnews->get_cate_news();
    $xoopsTpl->assign('ncsn', '');
}

//顯示單一新聞
function show_news($nsn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $xoopsUser, $Tadnews;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $Tadnews->set_show_enable(0);
    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    $Tadnews->get_news();
    $xoopsTpl->assign('uid', $uid);
    $xoopsTpl->assign('show_next_btn', $xoopsModuleConfig['show_next_btn']);

}
