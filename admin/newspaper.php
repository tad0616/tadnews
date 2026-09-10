<?php
use Xmf\Request;
use XoopsModules\Tadnews\Paper;

$xoopsOption['template_main'] = 'tadnews_adm_newspaper.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------執行動作判斷區----------*/
$op     = Request::getString('op');
$memail = Request::getString('memail');
$mail   = Request::getString('mail');
$nps_sn = Request::getInt('nps_sn');
$npsn   = Request::getInt('npsn');
$g2p    = Request::getInt('g2p');

$xoopsTpl->assign('op', $op);

switch ($op) {
    case 'save_newspaper_set':
        $nps_sn = Paper::save_newspaper_set($nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=add_newspaper&nps_sn={$nps_sn}");
        exit;

    //刪除電子報設定組
    case 'del_newspaper_set':
        Utility::xoops_security_check();
        Paper::del_newspaper_set($nps_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //編輯資料
    case 'add_newspaper':
        Paper::add_newspaper($nps_sn);
        break;
    case 'save_newspaper':
        $npsn = Paper::save_newspaper();
        header("location: {$_SERVER['PHP_SELF']}?op=edit_newspaper&npsn={$npsn}");
        exit;

    //編輯電子報資料
    case 'edit_newspaper':
        Paper::edit_newspaper($npsn);
        break;
    case 'save_all':
        Paper::save_all($npsn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail&npsn={$npsn}");
        exit;

    //刪除電子報
    case 'del_newspaper':
        Utility::xoops_security_check();
        Paper::del_newspaper($npsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'sendmail':
        Paper::sendmail_form($npsn);
        break;
    case 'send_now':
        Paper::send_now($npsn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail_log&npsn={$npsn}");
        exit;

    case 'sendmail_log':
        Paper::sendmail_log($npsn);
        break;
    case 'preview':
        $main = Paper::preview_newspaper($npsn);
        break;
    case 'modify':
        Paper::open_newspaper($nps_sn);
        break;
    case 'creat_newspaper':
        Paper::open_newspaper();
        break;
    case 'newspaper_email':
        Paper::newspaper_email($nps_sn, $memail, $g2p);
        break;
    //刪除電子郵件
    case 'delete_tad_news_email':
        Paper::delete_tad_news_email($mail, $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn&g2p={$g2p}");
        exit;

    //刪除電子郵件
    case 'delete_tad_news_email_npsn':
        Utility::xoops_security_check();
        Paper::delete_tad_news_email($mail, $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail&npsn=$npsn");
        exit;

    //更新電子郵件
    case 'update_email':
        Paper::update_email($_POST['old_email'], $_POST['new_email'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn&g2p={$g2p}");
        exit;

    //匯入電子郵件
    case 'email_import':
        Paper::email_import($_POST['email_import'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn");
        exit;

    default:
        Paper::newspaper_set_table($nps_sn);
        break;
}

/*-----------秀出結果區--------------*/
if ('preview' === $op) {
    echo $main;
} else {
    require_once __DIR__ . '/footer.php';
}
