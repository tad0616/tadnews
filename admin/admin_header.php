<?php

require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

$moduleDirName = basename(dirname(__DIR__));
xoops_loadLanguage('main', $moduleDirName);
