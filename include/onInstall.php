<?php
use XoopsModules\Tadtools\Utility;

include dirname(__DIR__) . '/preloads/autoloader.php';

function xoops_module_install_tadnews(&$module)
{
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/cate');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/file');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/image');
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/image/.thumbs');

    //建立電子報佈景
    Utility::mk_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes');
    if (!is_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes/bluefreedom2')) {
        Utility::full_copy(XOOPS_ROOT_PATH . '/modules/tadnews/images/bluefreedom2', XOOPS_ROOT_PATH . '/uploads/tadnews/themes/bluefreedom2');
    }

    return true;
}
