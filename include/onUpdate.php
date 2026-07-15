<?php

use XoopsModules\Tadnews\Update;
use XoopsModules\Tadtools\Utility;

if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
if (!class_exists('XoopsModules\Tadnews\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}

function xoops_module_update_tadnews(&$module, $old_version)
{
    Update::chk_tad_news();
    Update::chk_tadnews_files_center();
    Update::chk_tad_news_tags();
    Update::chk_tadnews_data_center();
    Update::chk_other_tables();
    Update::chk_cleanup();

    return true;
}
