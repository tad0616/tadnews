<?php
use Xmf\Request;
use XoopsModules\Tadnews\Tag;

$xoopsOption['template_main'] = 'tadnews_adm_tag.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op     = Request::getString('op');
$tag_sn = Request::getInt('tag_sn');
$enable = Request::getInt('enable');

switch ($op) {
    //新增資料
    case 'insert_tad_news_tags':
        $tag_sn = Tag::insert_tad_news_tags();
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //更新資料
    case 'update_tad_news_tags':
        Tag::update_tad_news_tags($tag_sn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //關閉資料
    case 'stat':
        Tag::tad_news_tags_stat($enable, $tag_sn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //刪除資料
    case 'del_tag':
        Utility::xoops_security_check();
        Tag::del_tag($tag_sn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    default:
        Tag::list_tad_news_tags($tag_sn);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
