<?php
use XoopsModules\Tadnews\Cate;
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/Tadtools/preloads/autoloader.php';
}

$Tadnews = new Tadnews();
