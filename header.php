<?php
use XoopsModules\Tadtools\Utility;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';

require_once __DIR__ . '/function.php';

if ('1' == $xoopsModuleConfig['use_pda'] and false === mb_strpos($_SERVER['PHP_SELF'], 'ajax.php') and false === mb_strpos($_SESSION['theme_kind'], 'bootstrap')) {
    $nsn = (isset($_REQUEST['nsn'])) ? (int) $_REQUEST['nsn'] : 0;
    $ncsn = (isset($_REQUEST['ncsn'])) ? (int) $_REQUEST['ncsn'] : 0;
    Utility::mobile_device_detect(true, false, true, true, true, true, true, "pda.php?nsn={$nsn}&ncsn={$ncsn}", false);

}

if ($xoopsUser) {
    $module_id = $xoopsModule->getVar('mid');
    $isAdmin = $xoopsUser->isAdmin($module_id);
} else {
    $isAdmin = false;
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
    $and_ncsn = empty($_REQUEST['ncsn']) ? '' : "?ncsn={$_REQUEST['ncsn']}";
    $interface_menu[_MD_TADNEWS_POST] = "post.php{$and_ncsn}";
    $interface_menu[_MD_TADNEWS_KIND_PAGE] = 'page.php';
    $interface_menu[_MD_TADNEWS_MY] = 'my_news.php';
}

// $interface_menu['RSS'] = empty($_REQUEST['ncsn']) ? "rss.php" : "rss.php?ncsn={$_REQUEST['ncsn']}";

if ($isAdmin) {
    $interface_menu[_MD_TADNEWS_TO_ADMIN] = 'admin/main.php';
}
