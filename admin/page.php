<?php
$xoopsOption['template_main'] = 'tadnews_adm_page.html';
include_once "header.php";
include_once "../function.php";
include_once "admin_function.php";

/*-----------function區--------------*/

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn     = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$to_ncsn  = system_CleanVars($_REQUEST, 'to_ncsn', 0, 'int');
$nsn      = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');

switch ($op) {

    //新增資料
    case "insert_tad_news_cate":
        $ncsn = insert_tad_news_cate();
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //更新資料
    case "update_tad_news_cate";
        update_tad_news_cate($ncsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //刪除資料
    case "delete_tad_news_cate";
        delete_tad_news_cate($ncsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //刪除資料
    case "delete_tad_news";
        $tadnews->delete_tad_news($nsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //批次管理
    case "batch":
        if ($_POST['act'] == "move_news") {
            move_news($_POST['nsn_arr'], $ncsn);
        } elseif ($_POST['act'] == "del_news") {
            del_news($_POST['nsn_arr']);
        }
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    default:
        list_tad_news($ncsn, "page", $show_uid);

        break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
