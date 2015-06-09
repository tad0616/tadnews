<?php

include '../../mainfile.php';

$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;

if ($com_itemid > 0) {
    $sql                   = "select news_title,news_content from " . $xoopsDB->prefix("tad_news") . " where nsn='{$com_itemid}'";
    $result                = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, show_error($sql));
    list($title, $content) = $xoopsDB->fetchRow($result);
    $com_replytext         = $content;
}

$com_replytitle = "RE:{$title}";

include XOOPS_ROOT_PATH . '/include/comment_new.php';
