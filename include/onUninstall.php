<?php

function xoops_module_uninstall_tadnews(&$module)
{
    global $xoopsDB;

    $date = date('Ymd');

    rename(XOOPS_ROOT_PATH . '/uploads/tadnews', XOOPS_ROOT_PATH . "/uploads/tadnews_bak_{$date}");

    return true;
}
