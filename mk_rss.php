<?php
use XoopsModules\Tadtools\Utility;

function mk_rss()
{
    global $xoopsDB, $xoopsConfig;
    xoops_load('XoopsLocal');
    $myts   = \MyTextSanitizer::getInstance();
    $sql    = 'SELECT `ncsn`,`nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`!=? AND `enable_group`=?';
    $result = Utility::query($sql, 'ss', [1, '']) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $ncsn_ok[]    = $ncsn;
        $cates[$ncsn] = $nc_title;
    }

    $ok_cate    = implode(',', (Array) $ncsn_ok);
    $where_cate = empty($ok_cate) ? "AND ncsn = '0'" : "AND (ncsn IN ($ok_cate) OR ncsn = '0')";
    $today      = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'SELECT *
        FROM `' . $xoopsDB->prefix('tad_news') . '`
        WHERE `enable` = ?
        AND `passwd` = ?
        AND `enable_group` = ?
        ' . $where_cate . '
        AND `start_day` < ?
        AND (`end_day` > ? OR `end_day` = ?)
        ORDER BY `start_day` DESC
        LIMIT 0, 30';

    $params = ['1', '', '', $today, $today, '0000-00-00 00:00:00'];
    $result = Utility::query($sql, str_repeat('s', count($params)), $params) or redirect_header(XOOPS_URL, 3, $sql);

    $allItem = '';
    while (false !== ($all_news = $xoopsDB->fetchArray($result))) {
        foreach ($all_news as $k => $v) {
            $$k = $v;
        }
        $news_title   = $myts->htmlSpecialChars($news_title);
        $news_content = $myts->displayTarea($news_content, 1, 1, 1, 1, 0);

        $allItem .= '
        <item>
          <title>' . XoopsLocal::convert_encoding(htmlspecialchars($news_title, ENT_QUOTES)) . '</title>
          <link>' . XOOPS_URL . "/modules/tadnews/index.php?nsn={$nsn}</link>
          <description>" . XoopsLocal::convert_encoding(htmlspecialchars($news_content, ENT_QUOTES)) . '</description>

          <pubDate>' . formatTimestamp(strtotime($start_day), 'rss') . '</pubDate>
          <guid>' . XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}</guid>
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

    $main = '
    <?xml version="1.0" encoding="UTF-8"?>
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
