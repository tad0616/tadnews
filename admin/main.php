<?php
use Xmf\Request;
use XoopsModules\Tadnews\Cate;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_adm_main.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op       = Request::getString('op');
$ncsn     = Request::getInt('ncsn');
$nsn      = Request::getInt('nsn');
$show_uid = Request::getInt('show_uid');
$to_ncsn  = Request::getInt('to_ncsn');
$not_news = Request::getInt('not_news');

//先給預設值，Cate::tad_news_cate_form() 會在 switch 中覆寫成 tad_news_cate_form，
//故不可放到 switch 之後，否則分類表單會被蓋掉而不顯示
$xoopsTpl->assign('now_op', $op);

switch ($op) {
    //刪除資料
    case 'delete_tad_news':
        Utility::xoops_security_check();
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //批次管理
    case 'batch':
        if ('move_news' === $_POST['act']) {
            Cate::move_news($_POST['nsn_arr'], $ncsn);
        } elseif ('del_news' === $_POST['act']) {
            Cate::del_news($_POST['nsn_arr']);
        }
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    case 'modify_news_cate':
        Cate::cate_tree($ncsn, 0, 'main.php', 'news_tree');
        Cate::tad_news_cate_form($ncsn, 0);
        break;
    //新增資料
    case 'insert_tad_news_cate':
        $ncsn = Cate::insert_tad_news_cate();
        header('location: ' . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //更新資料
    case 'update_tad_news_cate':
        Cate::update_tad_news_cate($ncsn);
        header('location: ' . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //刪除資料
    case 'delete_tad_news_cate':
        Utility::xoops_security_check();
        Cate::delete_tad_news_cate($ncsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //搬移資料
    case 'move_to':
        Cate::move_to_cate($ncsn, $to_ncsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    case 'modify_page_cate':
        Cate::cate_tree($ncsn, 0, 'main.php', 'news_tree');
        Cate::tad_news_cate_form($ncsn, 0);
        break;

    //分類類型互轉
    case 'change_kind':
        Cate::change_kind($ncsn, $not_news);
        break;
    default:
        Cate::cate_tree($ncsn, 0, 'main.php', 'news_tree');
        Cate::list_tad_news($ncsn, 'news', $show_uid);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('cate_img_url', XOOPS_URL . '/uploads/tadnews/cate');
require_once __DIR__ . '/footer.php';
