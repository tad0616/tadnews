<?php
$adminmenu = array();
$i         = 1;
$icon_dir  = substr(XOOPS_VERSION, 6, 3) == '2.6' ? "" : "images/";

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMIN_HOME_DESC;
$adminmenu[$i]['icon']  = 'images/admin/home.png';
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU1;
$adminmenu[$i]['link']  = "admin/main.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU1;
$adminmenu[$i]['icon']  = "images/admin/folder_txt.png";
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU3;
$adminmenu[$i]['link']  = "admin/cate.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU3;
$adminmenu[$i]['icon']  = "{$icon_dir}view_detailed.png";
$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU5;
$adminmenu[$i]['link']  = "admin/newspaper.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU5;
$adminmenu[$i]['icon']  = "{$icon_dir}newsletter.png";
$i++;

$modhandler      = &xoops_gethandler('module');
$newsxoopsModule = &$modhandler->getByDirname("news");
if ($newsxoopsModule) {
    $adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU4;
    $adminmenu[$i]['link']  = "admin/import.php";
    $adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU4;
    $adminmenu[$i]['icon']  = "{$icon_dir}synchronized.png";
    $i++;
}

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU9;
$adminmenu[$i]['link']  = "admin/page_cate.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU9;
$adminmenu[$i]['icon']  = "{$icon_dir}view_detailed.png";

$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU7;
$adminmenu[$i]['link']  = "admin/page.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU7;
$adminmenu[$i]['icon']  = "{$icon_dir}content.png";

$i++;

$adminmenu[$i]['title'] = _MI_TADNEWS_ADMENU8;
$adminmenu[$i]['link']  = "admin/tag.php";
$adminmenu[$i]['desc']  = _MI_TADNEWS_ADMENU8;
$adminmenu[$i]['icon']  = "{$icon_dir}groupmod.png";
$i++;
