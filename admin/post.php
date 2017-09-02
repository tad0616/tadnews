<?php
$xoopsOption['template_main'] = 'tadnews_post.tpl';
include_once "header.php";
include_once "../function.php";
include_once "admin_function.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op  = system_CleanVars($_REQUEST, 'op', '', 'string');
$nsn = system_CleanVars($_REQUEST, 'nsn', 0, 'int');

switch ($op) {

    //新增資料
    case "insert_tad_news":
        $nsn = $tadnews->insert_tad_news();
        break;

    //輸入表格
    case "tad_news_form":
        $tadnews->tad_news_form($nsn);
        break;

    //更新資料
    case "update_tad_news":
        $tadnews->update_tad_news($nsn);
        header("location: ../index.php?nsn={$nsn}");
        exit;
        break;

    default:
        $tadnews->tad_news_form($nsn);
        break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
