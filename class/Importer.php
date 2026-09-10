<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\Utility;

/**
 * 從舊 news 模組匯入分類與文章（一次性遷移工具）
 * 唯讀存取非本模組所有的外部表：topics、stories。
 * 由 admin/import.php 搬入（2026-08-14），行為未變更。
 */
class Importer
{
    //檢查有無安裝新聞區模組
    public static function chk_news_mod($version)
    {
        global $xoopsDB, $xoopsTpl;

        if (empty($version)) {
            $main = _MA_TADNEWS_NO_NEWSMOD;
        } else {

            $main = "<form action='{$_SERVER['PHP_SELF']}' method='post'>
            <div class='bar m-3'>
                <button type='submit' name='op' value='import' class='btn btn-primary'>" . _MA_TADNEWS_IMPORT . "</button>
            </div>

            <table id='tbl'>
            <tr><th>" . _MA_TADNEWS_IMPORT_CATE . '</th></tr>';
            $main .= self::chk_cate();
            $main .= "</table>
            <div class='bar m-3ß'>
                <button type='submit' name='op' value='import' class='btn btn-primary'>" . _MA_TADNEWS_IMPORT . "</button>
            </div>
            </form>";

            $main .= sprintf(_MA_TADNEWS_HAVE_NEWSMOD, $version, $_SESSION['total_cate'], $_SESSION['total_news']);
        }

        $xoopsTpl->assign('main', $main);
    }

    //檢查分類
    public static function chk_cate($topic_pid = '', $left = 0)
    {
        global $xoopsDB;

        if (!empty($topic_pid)) {
            $left += 14;
        }

        $sql = 'SELECT `topic_id`, `topic_pid`, `topic_title` FROM `' . $xoopsDB->prefix('topics') . '` WHERE `topic_pid`=?';
        $result = Utility::query($sql, 'i', [$topic_pid]) or Utility::web_error($sql, __FILE__, __LINE__);

        $main = '';

        while (list($topic_id, $topic_pid, $topic_title) = $xoopsDB->fetchRow($result)) {
            $_SESSION['total_cate']++;
            $main .= "<tr class='even'><td style='padding-left:{$left}px'><input type='checkbox' name='cate[$topic_pid][$topic_id]' checked=checked value='{$topic_title} '><b>$topic_title</b></td></tr>";
            $main .= self::chk_stories($topic_id, $left);
            $main .= self::chk_cate($topic_id, $left);
        }

        return $main;
    }

    //檢查文章
    public static function chk_stories($topicid = '', $left = 0)
    {
        global $xoopsDB;

        $left += 14;

        $sql = 'SELECT `storyid`, `title` FROM `' . $xoopsDB->prefix('stories') . '` WHERE `topicid` =?';
        $result = Utility::query($sql, 'i', [$topicid]) or Utility::web_error($sql, __FILE__, __LINE__);

        $main = '';
        while (list($storyid, $title) = $xoopsDB->fetchRow($result)) {
            $_SESSION['total_news']++;
            $main .= "<tr><td style='padding-left:{$left}px'><input type='checkbox' name='stories[$topicid][]' value='{$storyid}' checked=checked>$title</td></tr>";
        }

        return $main;
    }

    //進行類別匯入
    public static function import($topic_pid = 0, $new_topic_pid = 0)
    {
        global $xoopsDB;

        //匯入分類
        foreach ($_POST['cate'][$topic_pid] as $topic_id => $topic_title) {
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_cate') . "` (`of_ncsn`, `nc_title`, `enable_group`, `sort`) VALUES (?, ?, '', '')";
            if (Utility::query($sql, 'is', [$new_topic_pid, $topic_title])) {
                $sub_new_topic_pid = $xoopsDB->getInsertId();

                //匯入文章
                self::import_stories($topic_id, $sub_new_topic_pid);

                self::import($topic_id, $sub_new_topic_pid);
            }
        }
    }

    public static function import_stories($topicid = 0, $new_topic_pid = 0)
    {
        global $xoopsDB;

        $myts = \MyTextSanitizer::getInstance();

        foreach ($_POST['stories'][$topicid] as $storyid) {
            //找出勾選的內容
            $sql = 'SELECT `storyid`, `uid`, `title`, `published`, `expired`, `nohtml`, `nosmiley`, `hometext`, `bodytext`, `counter`, `topicid` FROM `' . $xoopsDB->prefix('stories') . '` WHERE `storyid` =?';
            $result = Utility::query($sql, 'i', [$storyid]) or Utility::web_error($sql, __FILE__, __LINE__);

            list($storyid, $uid, $title, $published, $expired, $nohtml, $nosmiley, $hometext, $bodytext, $counter, $topicid) = $xoopsDB->fetchRow($result);
            $news_content = $hometext . $bodytext;

            $myts = \MyTextSanitizer::getInstance();

            //bbcode 轉換
            $news_content = $myts->displayTarea($news_content, 1, 1, 1);

            $published = date('Y-m-d H:i:s', $published);
            $enable = (empty($expired)) ? '1' : '0';

            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news') . '` (`ncsn`, `news_title`, `news_content`, `start_day`, `end_day`, `enable`, `uid`, `passwd`, `enable_group`, `counter`) VALUES (?, ?, ?, ?, ?, ?, ?,?, ?, ?)';
            Utility::query($sql, 'isssssissi', [$new_topic_pid, $title, $news_content, $published, '', $enable, $uid, '', '', $counter]);
        }
    }
}
