<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require dirname(dirname(__DIR__)) . '/mainfile.php';

$com_itemid = Request::getInt('com_itemid');

if ($com_itemid > 0) {
    $sql = 'select news_title,news_content from ' . $xoopsDB->prefix('tad_news') . " where nsn='{$com_itemid}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($title, $content) = $xoopsDB->fetchRow($result);
    $com_replytext = $content;
}

$com_replytitle = "RE:{$title}";

require XOOPS_ROOT_PATH . '/include/comment_new.php';
