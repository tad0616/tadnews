<?php
$adminmenu = [];
$i = 1;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMIN_HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['desc'] = _MI_TADNEWS_ADMIN_HOME_DESC;
$adminmenu[$i]['icon'] = 'images/admin/home.png';
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU1;
$adminmenu[$i]['link'] = 'admin/main.php';
$adminmenu[$i]['desc'] = _MI_TADNEWS_ADMENU1;
$adminmenu[$i]['icon'] = 'images/admin/folder_txt.png';
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU5;
$adminmenu[$i]['link'] = 'admin/newspaper.php';
$adminmenu[$i]['desc'] = _MI_TADNEWS_ADMENU5;
$adminmenu[$i]['icon'] = 'images/admin/newsletter.png';
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU7;
$adminmenu[$i]['link'] = 'admin/page.php';
$adminmenu[$i]['desc'] = _MI_TADNEWS_ADMENU7;
$adminmenu[$i]['icon'] = 'images/admin/content.png';

$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU8;
$adminmenu[$i]['link'] = 'admin/tag.php';
$adminmenu[$i]['desc'] = _MI_TADNEWS_ADMENU8;
$adminmenu[$i]['icon'] = 'images/admin/groupmod.png';
$i++;

$modhandler = xoops_getHandler('module');
$newsxoopsModule = $modhandler->getByDirname('news');
if ($newsxoopsModule) {
    $adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU4;
    $adminmenu[$i]['link'] = 'admin/import.php';
    $adminmenu[$i]['desc'] = _MI_TADNEWS_ADMENU4;
    $adminmenu[$i]['icon'] = 'images/admin/synchronized.png';
    $i++;
}
