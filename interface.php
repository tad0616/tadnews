<?php

use Xmf\Request;
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

$nsn = Request::getInt('nsn');
$ncsn = Request::getInt('ncsn');

//判斷是否對該模組有管理權限
if (!isset($tadnews_adm)) {
    $tadnews_adm = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_TADNEWS_NAME] = 'index.php';
$interface_icon[_TADNEWS_NAME] = 'fa-home';
if (!isset($xoopsModuleConfig)) {
    $tadnewsModuleConfig = Utility::getXoopsModuleConfig('tadnews');
} else {
    $tadnewsModuleConfig = $xoopsModuleConfig;
}

if ('1' == $tadnewsModuleConfig['use_archive']) {
    $interface_menu[_MD_TADNEWS_ARCHIVE] = 'archive.php';
    $interface_icon[_MD_TADNEWS_ARCHIVE] = 'fa-files-o';
}

if ('1' == $tadnewsModuleConfig['use_newspaper']) {
    $interface_menu[_MD_TADNEWS_NEWSPAPER] = 'newspaper.php';
    $interface_icon[_MD_TADNEWS_NEWSPAPER] = 'fa-newspaper-o';
}

$p = Tools::chk_user_cate_power();
if (isset($xoopsUser) && \is_object($xoopsUser) && is_array($p) && count($p) > 0) {
    $and_ncsn = empty($ncsn) ? '' : "?ncsn={$ncsn}";
    $interface_menu[_MD_TADNEWS_POST] = "post.php{$and_ncsn}";
    $interface_icon[_MD_TADNEWS_POST] = 'fa-pencil-square-o';
    $interface_menu[_MD_TADNEWS_KIND_PAGE] = 'page.php';
    $interface_icon[_MD_TADNEWS_KIND_PAGE] = 'fa-file-text-o';
    $interface_menu[_MD_TADNEWS_MY] = 'my_news.php';
    $interface_icon[_MD_TADNEWS_MY] = 'fa-address-book';
}

if ($tadnews_adm) {
    $interface_menu[_MD_TADNEWS_TO_ADMIN] = 'admin/main.php';
    $interface_icon[_MD_TADNEWS_TO_ADMIN] = 'fa-sign-in';
}
