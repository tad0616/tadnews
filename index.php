<?php
use XoopsModules\Tadtools\StarRating;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

/*-----------function區--------------*/

//列出所有tad_news資料(summary模式)
function list_tad_summary_news($the_ncsn = '', $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary('300');
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    if ($the_ncsn > 0) {
        $Tadnews->set_view_ncsn($the_ncsn);
        $Tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
        $xoopsTpl->assign('cate', $Tadnews->get_tad_news_cate($the_ncsn));
    } else {
        $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    }
    $Tadnews->set_cover(true, 'db');

    //if($xoopsModuleConfig['use_star_rating']=='1'){
    //  $Tadnews->set_use_star_rating(true);
    //}

    $Tadnews->get_news();
    $xoopsTpl->assign('ncsn', $the_ncsn);
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}

//列出所有tad_news資料
function list_tad_all_news($the_ncsn = '', $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    if ($the_ncsn > 0) {
        $Tadnews->set_view_ncsn($the_ncsn);
        $Tadnews->set_show_mode($xoopsModuleConfig['cate_show_mode']);
        $xoopsTpl->assign('cate', $Tadnews->get_tad_news_cate($the_ncsn));
    } else {
        $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    }
    $Tadnews->get_news();
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
    $xoopsTpl->assign('ncsn', $the_ncsn);
}

//列出所有tad_news資料
function list_tad_tag_news($tag_sn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_view_tag($tag_sn);

    $Tadnews->get_news();
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}

//列出所有tad_news資料
function list_tad_cate_news($show_ncsn = 0, $the_level = 0, $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $Tadnews;

    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    $Tadnews->get_cate_news();
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
    $xoopsTpl->assign('ncsn', $show_ncsn);
}

//顯示單一新聞
function show_news($nsn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $xoopsUser, $Tadnews;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $Tadnews->set_show_enable(0);
    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    //if($xoopsModuleConfig['use_star_rating']=='1'){
    //  $Tadnews->set_use_star_rating(true);
    //}
    $Tadnews->get_news();
    $xoopsTpl->assign('uid', $uid);
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}

//已經讀過
function have_read($nsn = '', $uid = '')
{
    global $xoopsDB, $xoopsUser;
    //安全判斷
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
    }
    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'insert into ' . $xoopsDB->prefix('tad_news_sign') . " (`nsn`,`uid`,`sign_time`) values('$nsn','$uid','{$now}')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//檢查置頂時間
function chk_always_top()
{
    global $xoopsDB, $xoopsUser;
    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set always_top='0' WHERE always_top_date <='{$now}' and always_top_date!='0000-00-00 00:00:00'";
    $xoopsDB->queryF($sql);
}

//列出簽收狀況
function list_sign($nsn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsOption, $xoopsTpl, $interface_menu, $Tadnews;
    $news = $Tadnews->get_tad_news($nsn);

    $sql = 'select uid,sign_time from ' . $xoopsDB->prefix('tad_news_sign') . " where nsn='$nsn' order by sign_time";
    $sign = [];
    $i = 0;
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($uid, $sign_time) = $xoopsDB->fetchRow($result)) {
        $uid_name = \XoopsUser::getUnameFromId($uid, 1);
        $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;
        $sign[$i]['uid'] = $uid;
        $sign[$i]['uid_name'] = $uid_name;
        $sign[$i]['sign_time'] = $sign_time;
        $i++;
    }

    $xoopsTpl->assign('news_title', sprintf(_MD_TADNEWS_SIGN_LOG, $news['news_title']));
    $xoopsTpl->assign('nsn', $nsn);
    $xoopsTpl->assign('sign', $sign);
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}

//列出某人狀況
function list_user_sign($uid = '')
{
    global $xoopsDB, $xoopsUser, $xoopsOption, $xoopsTpl, $Tadnews;
    $news = $Tadnews->get_tad_news($nsn);

    $uid_name = \XoopsUser::getUnameFromId($uid, 1);
    $uid_name = (empty($uid_name)) ? \XoopsUser::getUnameFromId($uid, 0) : $uid_name;

    $sql = 'select a.nsn,a.sign_time,b.news_title from ' . $xoopsDB->prefix('tad_news_sign') . ' as a left join ' . $xoopsDB->prefix('tad_news') . " as b on a.nsn=b.nsn where a.uid='$uid' order by a.sign_time desc";
    $sign = [];
    $i = 0;
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $myts = \MyTextSanitizer::getInstance();
    while (list($nsn, $sign_time, $news_title) = $xoopsDB->fetchRow($result)) {
        $news_title = $myts->htmlSpecialChars($news_title);
        $sign[$i]['nsn'] = $nsn;
        $sign[$i]['news_title'] = $news_title;
        $sign[$i]['sign_time'] = $sign_time;
        $i++;
    }

    $xoopsTpl->assign('uid', $uid);
    $xoopsTpl->assign('sign', $sign);
    $xoopsTpl->assign('uid_name', sprintf(_MD_TADNEWS_SIGN_LOG, $uid_name));
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
}
/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$fsn = system_CleanVars($_REQUEST, 'fsn', 0, 'int');
$uid = system_CleanVars($_REQUEST, 'uid', 0, 'int');
$kind = system_CleanVars($_REQUEST, 'kind', '', 'string');
$tag_sn = system_CleanVars($_REQUEST, 'tag_sn', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');
$mod_name = system_CleanVars($_REQUEST, 'mod_name', '', 'string');
$col_name = system_CleanVars($_REQUEST, 'col_name', '', 'string');
$col_sn = system_CleanVars($_REQUEST, 'col_sn', 0, 'int');
$rank = system_CleanVars($_REQUEST, 'rank', '', 'string');

switch ($op) {
    //下載檔案
    case 'tufdl':
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : '';
        $TadUpFiles = new TadUpFiles('tadnews');
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;

    //刪除資料
    case 'delete_tad_news':
        $Tadnews->delete_tad_news($nsn);
        header('location: ' . $_SERVER['PHP_SELF']);
        exit;

    //已經閱讀
    case 'have_read':
        have_read($nsn, $uid);
        header('location: ' . $_SERVER['PHP_SELF'] . "?nsn=$nsn");
        exit;

    //列出簽收狀況
    case 'list_sign':
        $GLOBALS['xoopsOption']['template_main'] = 'tadnews_sign.tpl';
        require XOOPS_ROOT_PATH . '/header.php';
        list_sign($nsn);
        $xoopsTpl->assign('op', $op);
        break;

    //列出某人狀況
    case 'list_user_sign':
        $GLOBALS['xoopsOption']['template_main'] = 'tadnews_sign.tpl';
        require XOOPS_ROOT_PATH . '/header.php';
        list_user_sign($uid);
        $xoopsTpl->assign('op', $op);
        break;

    case 'save_rating':
        StarRating::save_rating($mod_name, $col_name, $col_sn, $rank);
        break;
    default:

        //把過期的置頂文徹下
        chk_always_top();
        if (!empty($nsn)) {
            $GLOBALS['xoopsOption']['template_main'] = 'tadnews_news.tpl';
            require XOOPS_ROOT_PATH . '/header.php';
            show_news($nsn);
        } elseif (!empty($tag_sn)) {
            $GLOBALS['xoopsOption']['template_main'] = 'tadnews_list.tpl';
            require XOOPS_ROOT_PATH . '/header.php';
            list_tad_tag_news($tag_sn);
        } elseif (!empty($ncsn)) {
            if ('summary' === $xoopsModuleConfig['cate_show_mode']) {
                $GLOBALS['xoopsOption']['template_main'] = 'tadnews_index_summary.tpl';
                require XOOPS_ROOT_PATH . '/header.php';
                list_tad_summary_news($ncsn);
            } else {
                $GLOBALS['xoopsOption']['template_main'] = 'tadnews_list.tpl';
                require XOOPS_ROOT_PATH . '/header.php';
                list_tad_all_news($ncsn);
            }
        } else {
            if ('summary' === $xoopsModuleConfig['show_mode']) {
                $GLOBALS['xoopsOption']['template_main'] = 'tadnews_index_summary.tpl';
                require XOOPS_ROOT_PATH . '/header.php';
                list_tad_summary_news(null, $show_uid);
            } elseif ('cate' === $xoopsModuleConfig['show_mode']) {
                $GLOBALS['xoopsOption']['template_main'] = 'tadnews_index_cate.tpl';
                require XOOPS_ROOT_PATH . '/header.php';
                list_tad_cate_news(null, null, $show_uid);
            } else {
                $GLOBALS['xoopsOption']['template_main'] = 'tadnews_list.tpl';
                require XOOPS_ROOT_PATH . '/header.php';
                list_tad_all_news(null, $show_uid);
            }
        }

        break;
}

/*-----------秀出結果區--------------*/
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
