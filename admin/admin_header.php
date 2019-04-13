<?php
include_once 'header.php';
include_once '../function.php';
include_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.php';
include_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

// include the default language file for the admin interface
if (!@include_once(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/main.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/language/english/main.php';
}
