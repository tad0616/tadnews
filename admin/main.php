<?php
use Xmf\Request;
use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once __DIR__ . '/admin_function.php';

/*-----------執行動作判斷區----------*/
$op       = Request::getString('op');
$ncsn     = Request::getInt('ncsn');
$nsn      = Request::getInt('nsn');
$show_uid = Request::getInt('show_uid');
$to_ncsn  = Request::getInt('to_ncsn');
$not_news = Request::getInt('not_news');

switch ($op) {
    //刪除資料
    case 'delete_tad_news':
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //批次管理
    case 'batch':
        if ('move_news' === $_POST['act']) {
            move_news($_POST['nsn_arr'], $ncsn);
        } elseif ('del_news' === $_POST['act']) {
            del_news($_POST['nsn_arr']);
        }
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    case 'modify_news_cate':
        list_tadnews_cate_tree($ncsn);
        tad_news_cate_form($ncsn, 0);
        break;
    //新增資料
    case 'insert_tad_news_cate':
        $ncsn = insert_tad_news_cate();
        header('location: ' . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //更新資料
    case 'update_tad_news_cate':
        update_tad_news_cate($ncsn);
        header('location: ' . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //刪除資料
    case 'delete_tad_news_cate':
        delete_tad_news_cate($ncsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //搬移資料
    case 'move_to':
        move_to_cate($ncsn, $to_ncsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    case 'modify_page_cate':
        list_tadnews_cate_tree($ncsn);
        tad_news_cate_form($ncsn, 0);
        break;

    //分類類型互轉
    case 'change_kind':
        change_kind($ncsn, $not_news);
        break;
    default:
        list_tadnews_cate_tree($ncsn);
        list_tad_news($ncsn, 'news', $show_uid);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('cate_img_url', XOOPS_URL . '/uploads/tadnews/cate');
require_once __DIR__ . '/footer.php';

/*-----------function區--------------*/

//列出所有tad_news_cate資料
function list_tadnews_cate_tree($def_ncsn = '')
{
    global $xoopsDB, $xoopsTpl;

    $categoryHelper = new CategoryHelper('tad_news_cate', 'ncsn', 'of_ncsn', 'nc_title');
    $cate_count     = $categoryHelper->getCategoryCount('tad_news');

    $data[] = "{ id:0, pId:0, name:'" . _MA_TADNEWS_ALL_NEWS . "', url:'main.php', target:'_self', open:true}";

    $sql    = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`!=? ORDER BY `sort`';
    $result = Utility::query($sql, 's', [1]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $nc_title = addslashes($nc_title);

        $font_style      = $def_ncsn == $ncsn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $display_counter = empty($cate_count[$ncsn]) ? '' : " ({$cate_count[$ncsn]})";
        $data[]          = "{ id:{$ncsn}, pId:{$of_ncsn}, name:'{$nc_title}{$display_counter}', url:'main.php?ncsn={$ncsn}', open: true ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", (Array) $data);

    $Ztree = new Ztree('news_tree', $json, 'save_drag.php', 'save_cate_sort.php', 'of_ncsn', 'ncsn');
    $xoopsTpl->assign('ztree_code', $Ztree->render());

    return $data;
}
