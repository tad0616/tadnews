<?php
use Xmf\Request;
use XoopsModules\Tadnews\Paper;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';

$newspaper_email = Request::getString('newspaper_email');
$mode = Request::getString('mode');
$nps_sn = Request::getInt('nps_sn');
/*-----------執行動作判斷區----------*/
Paper::update_mail($nps_sn, $newspaper_email, $mode);
