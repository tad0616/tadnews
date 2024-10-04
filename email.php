<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require __DIR__ . '/header.php';

$newspaper_email = Request::getString('newspaper_email');
$mode = Request::getString('mode');
$nps_sn = Request::getInt('nps_sn');
/*-----------執行動作判斷區----------*/
update_mail($nps_sn, $newspaper_email, $mode);

/*-----------function區--------------*/
//編輯工具
function update_mail($nps_sn = '', $newspaper_email = '', $mode = '')
{
    global $xoopsDB, $xoopsUser;
    $newspaper_set = get_newspaper_set($nps_sn);

    if (empty($newspaper_email) or !check_email_mx($newspaper_email)) {
        redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ERROR_EMAIL, $newspaper_email));
        return;
    }

    if ('add' === $mode) {
        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $sql = 'REPLACE INTO `' . $xoopsDB->prefix('tad_news_paper_email') . '` (`nps_sn`, `email`, `order_date`) VALUES (?, ?, ?)';
        Utility::query($sql, 'iss', [$nps_sn, $newspaper_email, $now]) or redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ORDER_ERROR, $newspaper_set['title']));
        redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ORDER_SUCCESS, $newspaper_set['title']));
    } elseif ('del' === $mode) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_news_paper_email') . '` WHERE `email`=?';
        Utility::query($sql, 's', [$newspaper_email]) or redirect_header(XOOPS_URL, 3, sprintf(_TADNEWS_DEL_ERROR, $newspaper_set['title']));

        redirect_header(XOOPS_URL, 3, sprintf(_TADNEWS_DEL_SUCCESS, $newspaper_set['title']));
    }
}

//檢查Email
function check_email_mx($email)
{
    if ((preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) ||
        (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/', $email))) {
        return true;
    }

    return false;
}
