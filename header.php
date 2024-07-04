<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once 'preloads/autoloader.php';

require_once __DIR__ . '/function.php';

$nsn = Request::getInt('nsn');
$ncsn = Request::getInt('ncsn');

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tadnews_adm'])) {
    $_SESSION['tadnews_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_TADNEWS_NAME] = 'index.php';
$interface_icon[_TADNEWS_NAME] = 'fa-home';

if ('1' == $xoopsModuleConfig['use_archive']) {
    $interface_menu[_MD_TADNEWS_ARCHIVE] = 'archive.php';
    $interface_icon[_MD_TADNEWS_ARCHIVE] = 'fa-files-o';
}

if ('1' == $xoopsModuleConfig['use_newspaper']) {
    $interface_menu[_MD_TADNEWS_NEWSPAPER] = 'newspaper.php';
    $interface_icon[_MD_TADNEWS_NEWSPAPER] = 'fa-newspaper-o';
}

$p = $Tadnews->chk_user_cate_power();
if ($xoopsUser && count($p) > 0) {
    $and_ncsn = empty($ncsn) ? '' : "?ncsn={$ncsn}";
    $interface_menu[_MD_TADNEWS_POST] = "post.php{$and_ncsn}";
    $interface_icon[_MD_TADNEWS_POST] = 'fa-pencil-square-o';
    $interface_menu[_MD_TADNEWS_KIND_PAGE] = 'page.php';
    $interface_icon[_MD_TADNEWS_KIND_PAGE] = 'fa-file-text-o';
    $interface_menu[_MD_TADNEWS_MY] = 'my_news.php';
    $interface_icon[_MD_TADNEWS_MY] = 'fa-address-book';
}

if ($_SESSION['tadnews_adm']) {
    $interface_menu[_MD_TADNEWS_TO_ADMIN] = 'admin/main.php';
    $interface_icon[_MD_TADNEWS_TO_ADMIN] = 'fa-sign-in';
}
