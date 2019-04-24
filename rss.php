<?php
/*-----------引入檔案區--------------*/
include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/template.php';
include_once XOOPS_ROOT_PATH . '/modules/tadnews/class/tadnews.php';
/*-----------function區--------------*/

$ncsn = 0;
$cate = [];
if (isset($_GET['ncsn'])) {
    $ncsn = (int) $_GET['ncsn'];
    $cate = $tadnews->get_tad_news_cate($ncsn);
}
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
// header("Content-Type:text/xml; charset=utf-8");

$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(10);
if (!$tpl->is_cached('db:tadnews_rss.tpl')) {
    $tadnews->set_show_num(20);
    $tadnews->set_view_ncsn($ncsn);
    $tadnews->set_show_mode('summary');
    $tadnews->set_news_kind('news');
    $tadnews->set_summary('page_break');
    $tadnews->set_use_star_rating(false);
    $tadnews->set_cover(false);
    $all_news = $tadnews->get_news('return');

    $all_news['nc_title'] = empty($ncsn) ? _MD_TADNEWS_ALL_CATE : $cate['nc_title'];

    if (is_array($all_news['page'])) {
        $sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
        $slogan = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
        $tpl->assign('channel_title', Utility::to_utf8($sitename) . '-' . Utility::to_utf8($all_news['nc_title'], ENT_QUOTES));
        $tpl->assign('channel_link', XOOPS_URL . '/');
        $tpl->assign('channel_desc', Utility::to_utf8($slogan));
        $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
        $tpl->assign('channel_category', Utility::to_utf8($all_news['nc_title'], ENT_QUOTES));
        $tpl->assign('channel_generator', 'XOOPS');
        $tpl->assign('channel_language', _LANGCODE);
        $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');
        $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');
        if (empty($dimention[0])) {
            $width = 88;
        } else {
            $width = ($dimention[0] > 144) ? 144 : $dimention[0];
        }
        if (empty($dimention[1])) {
            $height = 31;
        } else {
            $height = ($dimention[1] > 400) ? 400 : $dimention[1];
        }
        $tpl->assign('image_width', $width);
        $tpl->assign('image_height', $height);
        //$count = $sarray;
        foreach ($all_news['page'] as $news) {
            $storytitle = htmlspecialchars($news['news_title'], ENT_QUOTES);
            $description = htmlspecialchars($news['content'], ENT_QUOTES);
            $tpl->append('items', [
                'title' => Utility::to_utf8($storytitle),
                'link' => XOOPS_URL . "/modules/tadnews/index.php?nsn={$news['nsn']}",
                'guid' => XOOPS_URL . "/modules/tadnews/index.php?nsn={$news['nsn']}",
                'pubdate' => formatTimestamp(strtotime($news['post_date']), 'rss'),
                'description' => Utility::to_utf8($description),
            ]);
        }
    }
}
$tpl->display('db:tadnews_rss.tpl');
