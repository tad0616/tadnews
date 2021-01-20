<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once 'preloads/autoloader.php';

require_once __DIR__ . '/function.php';

$nsn = Request::getInt('nsn');
$ncsn = Request::getInt('ncsn');
if ('1' == $xoopsModuleConfig['use_pda'] and false === mb_strpos($_SERVER['PHP_SELF'], 'ajax.php') and false === mb_strpos($_SESSION['theme_kind'], 'bootstrap')) {
    Utility::mobile_device_detect(true, false, true, true, true, true, true, "pda.php?nsn={$nsn}&ncsn={$ncsn}", false);
}

//判斷是否對該模組有管理權限
if (!isset($_SESSION['tadnews_adm'])) {
    $_SESSION['tadnews_adm'] = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

$interface_menu[_TADNEWS_NAME] = 'index.php';
if ('1' == $xoopsModuleConfig['use_archive']) {
    $interface_menu[_MD_TADNEWS_ARCHIVE] = 'archive.php';
}

if ('1' == $xoopsModuleConfig['use_newspaper']) {
    $interface_menu[_MD_TADNEWS_NEWSPAPER] = 'newspaper.php';
}

$p = $Tadnews->chk_user_cate_power();
if (count($p) > 0 and $xoopsUser) {
    $and_ncsn = empty($ncsn) ? '' : "?ncsn={$ncsn}";
    $interface_menu[_MD_TADNEWS_POST] = "post.php{$and_ncsn}";
    $interface_menu[_MD_TADNEWS_KIND_PAGE] = 'page.php';
    $interface_menu[_MD_TADNEWS_MY] = 'my_news.php';
}

if ($_SESSION['tadnews_adm']) {
    $interface_menu[_MD_TADNEWS_TO_ADMIN] = 'admin/main.php';
}
