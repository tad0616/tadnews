<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\Utility;

/**
 * 簽收：tad_news_sign 的寫入與查詢（單篇簽收名單、個人簽收紀錄）
 * 由 index.php 搬入（2026-08-14），行為未變更。
 */
class Sign
{
    //已經讀過
    public static function have_read($nsn = '', $uid = '')
    {
        global $xoopsDB;
        Utility::xoops_security_check('', '', 'index.php');
        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_sign') . '` (`nsn`, `uid`, `sign_time`) VALUES (?, ?, ?)';
        Utility::query($sql, 'iis', [$nsn, $uid, $now]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //列出簽收狀況
    public static function list_sign($nsn = '')
    {
        global $xoopsDB, $xoopsUser, $xoopsOption, $xoopsTpl, $Tadnews;
        $news = $Tadnews->get_tad_news($nsn);

        $sign   = [];
        $i      = 0;
        $sql    = 'SELECT `uid`, `sign_time` FROM `' . $xoopsDB->prefix('tad_news_sign') . '` WHERE `nsn`=? ORDER BY `sign_time`';
        $result = Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($uid, $sign_time) = $xoopsDB->fetchRow($result)) {
            $uid_name              = \XoopsUser::getUnameFromId($uid, 1);
            $uid_name              = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;
            $sign[$i]['uid']       = $uid;
            $sign[$i]['uid_name']  = $uid_name;
            $sign[$i]['sign_time'] = $sign_time;
            $i++;
        }

        $xoopsTpl->assign('news_title', sprintf(_MD_TADNEWS_SIGN_LOG, $news['news_title']));
        $xoopsTpl->assign('nsn', $nsn);
        $xoopsTpl->assign('sign', $sign);
    }

    //列出某人狀況
    public static function list_user_sign($uid = '')
    {
        global $xoopsDB, $xoopsUser, $xoopsOption, $xoopsTpl, $Tadnews;
        $news = $Tadnews->get_tad_news($nsn);

        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;

        $sign   = [];
        $i      = 0;
        $sql    = 'SELECT a.nsn, a.sign_time, b.news_title FROM `' . $xoopsDB->prefix('tad_news_sign') . '` AS a LEFT JOIN `' . $xoopsDB->prefix('tad_news') . '` AS b ON a.nsn = b.nsn WHERE a.uid =? ORDER BY a.sign_time DESC';
        $result = Utility::query($sql, 'i', [$uid]) or Utility::web_error($sql, __FILE__, __LINE__);

        $myts = \MyTextSanitizer::getInstance();
        while (list($nsn, $sign_time, $news_title) = $xoopsDB->fetchRow($result)) {
            $news_title             = $myts->htmlSpecialChars($news_title);
            $sign[$i]['nsn']        = $nsn;
            $sign[$i]['news_title'] = $news_title;
            $sign[$i]['sign_time']  = $sign_time;
            $i++;
        }

        $xoopsTpl->assign('uid', $uid);
        $xoopsTpl->assign('sign', $sign);
        $xoopsTpl->assign('uid_name', sprintf(_MD_TADNEWS_SIGN_LOG, $uid_name));
    }
}
