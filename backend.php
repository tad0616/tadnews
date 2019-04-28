<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once XOOPS_ROOT_PATH . '/modules/tadnews/class/tadnews.php';
echo mk_rss();

function mk_rss()
{
    global $xoopsDB, $xoopsConfig;
    xoops_load('XoopsLocal');

    $tadnews = new tadnews();
    $tadnews->set_show_num(20);
    $tadnews->set_show_mode('summary');
    $tadnews->set_news_kind('news');
    $tadnews->set_summary('page_break');
    $tadnews->set_use_star_rating(false);
    $tadnews->set_cover(false);
    $all_news = $tadnews->get_news('return');

    $allItem = '';
    foreach ($all_news['page'] as $news) {
        $allItem .= '
    <item>
      <title>' . XoopsLocal::convert_encoding(htmlspecialchars($news['news_title'], ENT_QUOTES)) . '</title>
      <link>' . XOOPS_URL . "/modules/tadnews/index.php?nsn={$news['nsn']}</link>
      <description>" . XoopsLocal::convert_encoding(htmlspecialchars($news['content'], ENT_QUOTES)) . '</description>

      <pubDate>' . formatTimestamp(strtotime($news['post_date']), 'rss') . '</pubDate>
      <guid>' . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$news['ncsn']}</guid>
    </item>
    ";
    }

    $dimension = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimension[0])) {
        $width = 88;
    } else {
        $width = ($dimension[0] > 144) ? 144 : $dimension[0];
    }
    if (empty($dimension[1])) {
        $height = 31;
    } else {
        $height = ($dimension[1] > 400) ? 400 : $dimension[1];
    }

    $main = '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>' . XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)) . '</title>
    <link>' . XOOPS_URL . '</link>
    <description>' . XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)) . '</description>
    <lastBuildDate>' . formatTimestamp(time(), 'rss') . '</lastBuildDate>

    <generator>Tad News</generator>
    <category>News</category>
    <managingEditor>' . checkEmail($xoopsConfig['adminmail'], true) . '</managingEditor>
    <webMaster>' . checkEmail($xoopsConfig['adminmail'], true) . '</webMaster>
    <language>' . _LANGCODE . '</language>
    <image>

      <title>' . XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)) . '</title>
      <url>' . XOOPS_URL . '/images/logo.png</url>
      <link>' . XOOPS_URL . "</link>
      <width>{$width}</width>
      <height>{$height}</height>
    </image>

    $allItem
  </channel>
</rss>
  ";

    return $main;
}
