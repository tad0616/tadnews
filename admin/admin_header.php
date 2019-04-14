<?php
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

// include the default language file for the admin interface
if (!@include_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/main.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/main.php';
}
