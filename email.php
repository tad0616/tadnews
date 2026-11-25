<?php
use Xmf\Request;
use XoopsModules\Tadnews\Paper;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';

$op              = Request::getString('op');
$newspaper_email = Request::getString('newspaper_email');
$mode            = Request::getString('mode');
$nps_sn          = Request::getInt('nps_sn');
/*-----------執行動作判斷區----------*/
if ('confirm' === $op) {
    $exp = Request::getInt('exp');
    $sig = Request::getString('sig');
    Paper::confirm_mail($nps_sn, $newspaper_email, $mode, $exp, $sig);
} else {
    if ('POST' !== ($_SERVER['REQUEST_METHOD'] ?? '')) {
        redirect_header(XOOPS_URL, 3, _TAD_PERMISSION_DENIED);
        exit;
    }
    Utility::xoops_security_check('', '', XOOPS_URL);
    Paper::update_mail($nps_sn, $newspaper_email, $mode);
}
