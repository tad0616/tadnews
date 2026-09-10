<?php
use Xmf\Request;
use XoopsModules\Tadnews\Importer;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_adm_import.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
$_SESSION['total_news'] = $_SESSION['total_cate'] = 0;

$moduleHandler = xoops_getHandler('module');
$news = $moduleHandler->getByDirname('news');
if (!empty($news)) {
    $mid_news = $news->getVar('mid');
    $version = $news->getVar('version');

    $moduleHandler2 = xoops_getHandler('module');
    $mod_tadnews = $moduleHandler->getByDirname('tadnews');
    $mid_tadnews = $mod_tadnews->getVar('mid');
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');

switch ($op) {
    //刪除資料
    case 'import':
        Importer::import();
        header('location: index.php');
        exit;

    default:
        Importer::chk_news_mod($version);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
