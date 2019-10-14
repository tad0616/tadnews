<?php
use XoopsModules\Tadnews\Tadnews;
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_post.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once __DIR__ . '/admin_function.php';

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$nsn = system_CleanVars($_REQUEST, 'nsn', 0, 'int');

switch ($op) {
    //新增資料
    case 'insert_tad_news':
        $nsn = $Tadnews->insert_tad_news();
        break;
    //輸入表格
    case 'tad_news_form':
        $Tadnews->tad_news_form($nsn);
        break;
    //更新資料
    case 'update_tad_news':
        $Tadnews->update_tad_news($nsn);
        header("location: ../index.php?nsn={$nsn}");
        exit;

    default:
        $Tadnews->tad_news_form($nsn);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
