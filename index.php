<?php
use Xmf\Request;
use XoopsModules\Tadtools\StarRating;
use XoopsModules\Tadtools\TadUpFiles;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = 'tadnews_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------function區--------------*/

//列出所有tad_news資料(summary模式)
function list_tad_summary_news($the_ncsn = '', $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

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

    $Tadnews->get_news();
    $xoopsTpl->assign('ncsn', $the_ncsn);

    //目前路徑
    if ($the_ncsn) {
        $path = [];
        $arr = get_tadnews_cate_path($the_ncsn);
        $path = Utility::tad_breadcrumb($the_ncsn, $arr, 'index.php', 'ncsn', 'nc_title');
        $xoopsTpl->assign('path', $path);
    }
}

//列出所有tad_news資料
function list_tad_all_news($the_ncsn = '', $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

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
    $xoopsTpl->assign('ncsn', $the_ncsn);

    //目前路徑
    if ($the_ncsn) {
        $path = [];
        $arr = get_tadnews_cate_path($the_ncsn);
        $path = Utility::tad_breadcrumb($the_ncsn, $arr, 'index.php', 'ncsn', 'nc_title');
        $xoopsTpl->assign('path', $path);
    }
}

//列出所有tad_news資料
function list_tad_tag_news($tag_sn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    $Tadnews->set_news_kind('news');
    $Tadnews->set_view_tag($tag_sn);

    $Tadnews->get_news();
}

//列出所有tad_news資料
function list_tad_cate_news($the_ncsn = 0, $the_level = 0, $show_uid = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $Tadnews;

    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode($xoopsModuleConfig['show_mode']);
    $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }
    $Tadnews->get_cate_news();
    $xoopsTpl->assign('ncsn', $the_ncsn);

    //目前路徑
    if ($the_ncsn) {
        $path = [];
        $arr = get_tadnews_cate_path($the_ncsn);
        $path = Utility::tad_breadcrumb($the_ncsn, $arr, 'index.php', 'ncsn', 'nc_title');
        $xoopsTpl->assign('path', $path);
    }
}

//顯示單一新聞
function show_news($nsn = '')
{
    global $xoopsModuleConfig, $xoopsTpl, $xoopsUser, $Tadnews;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $Tadnews->set_show_enable(0);
    $Tadnews->set_view_nsn($nsn);
    $Tadnews->set_cover(true, 'db');
    $Tadnews->set_summary('full');
    $Tadnews->get_news();
    $xoopsTpl->assign('uid', $uid);
    $xoopsTpl->assign('show_next_btn', $xoopsModuleConfig['show_next_btn']);

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
    global $xoopsDB, $xoopsUser, $xoopsOption, $xoopsTpl, $Tadnews;
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
}
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$nsn = Request::getInt('nsn');
$fsn = Request::getInt('fsn');
$uid = Request::getInt('uid');
$kind = Request::getString('kind');
$tag_sn = Request::getInt('tag_sn');
$show_uid = Request::getInt('show_uid');
$mod_name = Request::getString('mod_name');
$col_name = Request::getString('col_name');
$col_sn = Request::getInt('col_sn');
$rank = Request::getString('rank');
$files_sn = Request::getInt('files_sn');

switch ($op) {
    //下載檔案
    case 'tufdl':
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
        $op = 'list_sign';
        list_sign($nsn);
        $xoopsTpl->assign('op', $op);
        break;

    //列出某人狀況
    case 'list_user_sign':
        $op = 'list_user_sign';
        list_user_sign($uid);
        $xoopsTpl->assign('op', $op);
        break;

    case 'save_rating':
        StarRating::save_rating($mod_name, $col_name, $col_sn, $rank);
        break;
    default:

        $xoopsTpl->assign('show_rss', $xoopsModuleConfig['show_rss']);
        //把過期的置頂文徹下
        chk_always_top();
        if (!empty($nsn)) {
            $op = 'tadnews_show';
            show_news($nsn);
        } elseif (!empty($tag_sn)) {
            $op = 'tadnews_list';
            list_tad_tag_news($tag_sn);
        } elseif (!empty($ncsn)) {
            if ('summary' === $xoopsModuleConfig['cate_show_mode']) {
                $op = 'tadnews_summary';
                list_tad_summary_news($ncsn);
            } else {
                $op = 'tadnews_list';
                list_tad_all_news($ncsn);
            }
        } else {
            if ('summary' === $xoopsModuleConfig['show_mode']) {
                $op = 'tadnews_summary';
                list_tad_summary_news(null, $show_uid);
            } elseif ('cate' === $xoopsModuleConfig['show_mode']) {
                $op = 'tadnews_cate';
                list_tad_cate_news(null, null, $show_uid);
            } else {
                $op = 'tadnews_list';
                list_tad_all_news(null, $show_uid);
            }
        }

        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadnews/css/module.css');
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
