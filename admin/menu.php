<?php
$adminmenu = [
    [
        'title' => _MI_TADNEWS_ADMENU1,
        'link' => 'admin/main.php',
        'icon' => 'images/admin/folder_txt.png',
    ],
    [
        'title' => _MI_TADNEWS_ADMENU5,
        'link' => 'admin/newspaper.php',
        'icon' => 'images/admin/newsletter.png',
    ],
    [
        'title' => _MI_TADNEWS_ADMENU7,
        'link' => 'admin/page.php',
        'icon' => 'images/admin/content.png',
    ],
    [
        'title' => _MI_TADNEWS_ADMENU8,
        'link' => 'admin/tag.php',
        'icon' => 'images/admin/groupmod.png',
    ],
];

$moduleHandler = xoops_getHandler('module');
$newsxoopsModule = $moduleHandler->getByDirname('news');
if ($newsxoopsModule) {
    $adminmenu[] = [
        'title' => _MI_TADNEWS_ADMENU4,
        'link' => 'admin/import.php',
        'icon' => 'images/admin/synchronized.png',
    ];
}

$adminmenu[] = [
    'title' => _MI_TAD_ADMIN_ABOUT,
    'link' => 'admin/about.php',
    'icon' => 'images/admin/about.png',
];
