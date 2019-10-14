<?php
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_adm_import.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once __DIR__ . '/admin_function.php';

/*-----------function區--------------*/

$moduleHandler = xoops_getHandler('module');
$news = $moduleHandler->getByDirname('news');
if (!empty($news)) {
    $mid_news = $news->getVar('mid');
    $version = $news->getVar('version');

    $moduleHandler2 = xoops_getHandler('module');
    $mod_tadnews = $moduleHandler->getByDirname('tadnews');
    $mid_tadnews = $mod_tadnews->getVar('mid');
}

//檢查有無安裝新聞區模組
function chk_news_mod($version)
{
    global $xoopsDB, $xoopsTpl;

    if (empty($version)) {
        $main = _MA_TADNEWS_NO_NEWSMOD;
    } else {
        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $token = new \XoopsFormHiddenToken();
        $XOOPS_TOKEN = $token->render();

        $main = sprintf(_MA_TADNEWS_HAVE_NEWSMOD, $version);
        $main .= "<form action='{$_SERVER['PHP_SELF']}' method='post'>
        <center>
        <p><input type='submit' value='" . _MA_TADNEWS_IMPORT . "'></p>
        <table id='tbl'>
        <tr><th>" . _MA_TADNEWS_IMPORT_CATE . '</th></tr>';
        $main .= chk_cate();
        $main .= "</table>
        <input type='hidden' name='op' value='import'>
        $XOOPS_TOKEN
        <p><input type='submit' value='" . _MA_TADNEWS_IMPORT . "'></p>
        </center>
        </form>";
    }

    $xoopsTpl->assign('main', $main);
}

//檢查分類
function chk_cate($topic_pid = '', $left = 0)
{
    global $xoopsDB;

    if (!empty($topic_pid)) {
        $left += 14;
    }

    $sql = 'select topic_id,topic_pid,topic_title from ' . $xoopsDB->prefix('topics') . " where topic_pid='{$topic_pid}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $main = '';

    while (list($topic_id, $topic_pid, $topic_title) = $xoopsDB->fetchRow($result)) {
        $main .= "<tr class='even'><td style='padding-left:{$left}px'><input type='checkbox' name='cate[$topic_pid][$topic_id]' checked=checked value='{$topic_title} '><b>$topic_title</b></td></tr>";
        $main .= chk_stories($topic_id, $left);
        $main .= chk_cate($topic_id, $left);
    }

    return $main;
}

//檢查文章
function chk_stories($topicid = '', $left = 0)
{
    global $xoopsDB;

    $left += 14;

    $sql = 'select storyid,title  from ' . $xoopsDB->prefix('stories') . " where topicid ='{$topicid}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $main = '';
    while (list($storyid, $title) = $xoopsDB->fetchRow($result)) {
        $main .= "<tr><td style='padding-left:{$left}px'><input type='checkbox' name='stories[$topicid][]' value='{$storyid}' checked=checked>$title</td></tr>";
    }

    return $main;
}

//進行類別匯入
function import($topic_pid = 0, $new_topic_pid = 0)
{
    global $xoopsDB;
    //安全判斷
    // if (!$GLOBALS['xoopsSecurity']->check()) {
    //     $error = implode("<br>", $GLOBALS['xoopsSecurity']->getErrors());
    //     redirect_header("index.php", 3, $error);
    // }
    //匯入分類
    foreach ($_POST['cate'][$topic_pid] as $topic_id => $topic_title) {
        $sql = 'insert into ' . $xoopsDB->prefix('tad_news_cate') . " (`of_ncsn`, `nc_title`, `enable_group`, `sort`) values('{$new_topic_pid}', '{$topic_title}', '', '')";
        if ($xoopsDB->query($sql)) {
            $sub_new_topic_pid = $xoopsDB->getInsertId();

            //匯入文章
            import_stories($topic_id, $sub_new_topic_pid);

            import($topic_id, $sub_new_topic_pid);
        }
    }
}

function import_stories($topicid = 0, $new_topic_pid = 0)
{
    global $xoopsDB;
    //安全判斷
    // if (!$GLOBALS['xoopsSecurity']->check()) {
    //     $error = implode("<br>", $GLOBALS['xoopsSecurity']->getErrors());
    //     redirect_header("index.php", 3, $error);
    // }

    $myts = \MyTextSanitizer::getInstance();

    foreach ($_POST['stories'][$topicid] as $storyid) {
        //找出勾選的內容
        $sql = 'SELECT `storyid`, `uid`, `title`, `published`, `expired`, `nohtml`, `nosmiley`, `hometext`, `bodytext`, `counter`, `topicid` FROM ' . $xoopsDB->prefix('stories') . " WHERE `storyid` ='{$storyid}'";
        $result = $xoopsDB->query($sql);

        list($storyid, $uid, $title, $published, $expired, $nohtml, $nosmiley, $hometext, $bodytext, $counter, $topicid) = $xoopsDB->fetchRow($result);
        $news_content = $hometext . $bodytext;

        $myts = \MyTextSanitizer::getInstance();
        $news_content = $myts->addSlashes($news_content);
        $title = $myts->addSlashes($title);

        //bbcode 轉換
        $news_content = $myts->displayTarea($news_content, 1, 1, 1);

        $published = date('Y-m-d H:i:s', $published);
        $enable = (empty($expired)) ? '1' : '0';

        $sql = 'insert into ' . $xoopsDB->prefix('tad_news') . " (`ncsn`, `news_title`, `news_content`, `start_day`, `end_day`, `enable`, `uid`, `passwd`, `enable_group`, `counter`) values('{$new_topic_pid}', '{$title} ', '{$news_content} ', '{$published}', '', '{$enable}', '{$uid}', '', '', '{$counter}')";
        if ($xoopsDB->queryF($sql)) {
            $new_nsn = $xoopsDB->getInsertId();
            //匯入評論
            import_common($storyid, $new_nsn);
        }
    }
}

function import_common($storyid = '', $new_nsn = '')
{
    global $xoopsDB, $mid_news, $mid_tadnews;

    $sql = 'update ' . $xoopsDB->prefix('xoopscomments') . " set com_modid='{$mid_tadnews}',com_itemid='{$new_nsn}' where com_modid='{$mid_news}' and com_itemid='{$storyid}'";
    $xoopsDB->query($sql);
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');

switch ($op) {
    //刪除資料
    case 'import':
        import();
        header('location: index.php');
        exit;

    default:
        chk_news_mod($version);
        break;
}

/*-----------秀出結果區--------------*/
require_once __DIR__ . '/footer.php';
