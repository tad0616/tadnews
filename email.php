<?php
/*-----------引入檔案區--------------*/
include 'header.php';
/*-----------function區--------------*/

//編輯工具
function update_mail()
{
    global $xoopsDB, $xoopsUser;
    $newspaper_set = get_newspaper_set($_POST['nps_sn']);

    if (empty($_POST['newspaper_email']) or !check_email_mx($_POST['newspaper_email'])) {
        redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ERROR_EMAIL, $_POST['newspaper_email']));

        return;
    }

    if ('add' == $_POST['mode']) {
        $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
        $sql = 'replace into ' . $xoopsDB->prefix('tad_news_paper_email') . " (nps_sn,email,order_date) values('{$_POST['nps_sn']}','{$_POST['newspaper_email']}','{$now}')";
        $xoopsDB->query($sql) or redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ORDER_ERROR, $newspaper_set['title']));
        redirect_header(XOOPS_URL, 3, sprintf(_MD_TADNEWS_ORDER_SUCCESS, $newspaper_set['title']));
    } elseif ('del' == $_POST['mode']) {
        $sql = 'delete from ' . $xoopsDB->prefix('tad_news_paper_email') . " where email='{$_POST['newspaper_email']}'";
        $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, sprintf(_TADNEWS_DEL_ERROR, $newspaper_set['title']));
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

/*-----------執行動作判斷區----------*/
update_mail();
