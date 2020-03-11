<?php
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;

$xoopsOption['template_main'] = 'tadnews_adm_newspaper.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';
require_once __DIR__ . '/admin_function.php';

/*-----------function區--------------*/

//找出現有電子報
function newspaper_set_table($sel_nps_sn = '')
{
    global $xoopsDB, $xoopsTpl, $op;
    $option = '';
    //找出現有設定組
    $sql = 'SELECT nps_sn,title FROM ' . $xoopsDB->prefix('tad_news_paper_setup') . '';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (list($nps_sn, $title) = $xoopsDB->fetchRow($result)) {
        if ($sel_nps_sn == $nps_sn) {
            $selected = 'selected';
            $ptitle = $title;
        } else {
            $selected = '';
        }
        $option .= "<option value='{$nps_sn}' $selected>$title</option>";
    }

    if (empty($option)) {
        header("location:{$_SERVER['PHP_SELF']}?op=creat_newspaper");
    }

    $sql = 'select a.npsn,a.number,b.title,a.np_date from ' . $xoopsDB->prefix('tad_news_paper') . ' as a ,' . $xoopsDB->prefix('tad_news_paper_setup') . " as b where a.nps_sn=b.nps_sn and b.nps_sn='{$sel_nps_sn}' order by a.np_date desc";

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $total = $xoopsDB->getRowsNum($result);

    $js = "
        <script>
        function delete_tad_newspaper_set(){
            var sure = window.confirm('" . _TADNEWS_SURE_DEL . "');
            if (!sure)        return;
            location.href=\"{$_SERVER['PHP_SELF']}?op=del_newspaper_set&nps_sn={$sel_nps_sn}\";
        }

        function delete_tad_newspaper(npsn){
            var sure = window.confirm('" . _TADNEWS_SURE_DEL . "');
            if (!sure)        return;
            location.href=\"{$_SERVER['PHP_SELF']}?op=del_newspaper&npsn=\" + npsn;
        }
        </script>";

    //刪除按鈕
    $del_btn = '';
    if (!empty($sel_nps_sn) and empty($total)) {
        $del_btn = "<button onClick='delete_tad_newspaper_set()' class='btn btn-danger'>" . _MA_TADNEWS_NP_DEL . $ptitle . '</button>';
    } elseif (!empty($sel_nps_sn)) {
        $del_btn = sprintf(_MA_TADNEWS_NP_DEL_DESC, $total);
    }

    $edit_btn = '';

    //修改按鈕
    if (!empty($sel_nps_sn)) {
        $edit_btn = "
        <button onClick=\"location.href='{$_SERVER['PHP_SELF']}?op=modify&nps_sn={$sel_nps_sn}'\" class='btn btn-info'>" . _MA_TADNEWS_NP_MODIFY . "</button>

        <button onClick=\"location.href='{$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn={$sel_nps_sn}'\" class='btn btn-success'>" . _MA_TADNEWS_NP_EMAIL . "</button>

        <button onClick=\"location.href='{$_SERVER['PHP_SELF']}?op=add_newspaper&nps_sn={$sel_nps_sn}'\" class='btn btn-warning'>" . _MA_TADNEWS_NP_SELECT . '</button>';
    }

    $i = 0;
    $newspaper = [];
    while (list($allnpsn, $number, $title, $np_date) = $xoopsDB->fetchRow($result)) {
        $newspaper[$i]['allnpsn'] = $allnpsn;
        $newspaper[$i]['title'] = $title;
        $newspaper[$i]['number'] = sprintf(_MA_TADNEWS_NP_NUMBER_INPUT, $number);
        $newspaper[$i]['np_date'] = $np_date;
        $i++;
    }

    $create_btn = "<a href='{$_SERVER['PHP_SELF']}?op=creat_newspaper' class='btn btn-info'>" . _MA_TADNEWS_NP_CREATE . '</a>';

    $xoopsTpl->assign('js', $js);
    $xoopsTpl->assign('del_btn', $del_btn);
    $xoopsTpl->assign('edit_btn', $edit_btn);
    $xoopsTpl->assign('create_btn', $create_btn);
    $xoopsTpl->assign('newspaper', $newspaper);
    $xoopsTpl->assign('nps_sn', $sel_nps_sn);

    $xoopsTpl->assign('option', $option);
}

//選擇佈景
function newspaper_themes($themes = '')
{
    if (is_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes')) {
        if ($dh = opendir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes')) {
            $select = "<select name='themes' id='themes' class='form-control'>";
            while (false !== ($file = readdir($dh))) {
                if ('.' === $file or '..' === $file) {
                    continue;
                }

                if (is_dir(XOOPS_ROOT_PATH . '/uploads/tadnews/themes/' . $file)) {
                    $selected = ($themes == $file) ? 'selected' : '';
                    $select .= "<option value='$file' $selected>$file</option>";
                }
            }
            $select .= '</select>';
            closedir($dh);
        }
    }

    return $select;
}

//建立電子報
function open_newspaper($nps_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig, $xoopsTpl, $op;

    //修改模式
    $hidden = (empty($nps_sn)) ? '' : "<input type='hidden' name='nps_sn' value='{$nps_sn}'>";

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new \XoopsFormHiddenToken();
    $hidden .= $token->render();

    //取得主題資料
    $set = (empty($nps_sn)) ? ['themes' => null, 'title' => null, 'head' => null, 'foot' => null] : get_newspaper_set($nps_sn);

    //取得使用之佈景
    $nps_theme = newspaper_themes($set['themes']);

    $np_title = (empty($nps_sn)) ? (string) $xoopsConfig['sitename'] . _MA_TADNEWS_NP : $set['title'];

    $author = $xoopsUser->getVar('uname');

    $head = (empty($nps_sn) or empty($set['head'])) ? sprintf(_MA_TADNEWS_NP_HEAD_CONTENT, $xoopsConfig['sitename'], XOOPS_URL) : $set['head'];

    $foot = (empty($nps_sn) or empty($set['foot'])) ? sprintf(_MA_TADNEWS_NP_FOOT_CONTENT, $author, XOOPS_URL, $xoopsConfig['sitename'], $xoopsConfig['sitename'], $xoopsConfig['adminmail'], $xoopsConfig['adminmail'], XOOPS_URL, XOOPS_URL) : $set['foot'];

    $xoopsTpl->assign('nps_theme', $nps_theme);
    $xoopsTpl->assign('np_title', $np_title);
    $xoopsTpl->assign('head', $head);
    $xoopsTpl->assign('foot', $foot);
    $xoopsTpl->assign('hidden', $hidden);
}

//儲存電子報設定
function save_newspaper_set($nps_sn = '')
{
    global $xoopsDB, $xoopsUser;
    //安全判斷
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
    }
    $myts = \MyTextSanitizer::getInstance();
    $title = $myts->addSlashes($_POST['title']);
    $head = $myts->addSlashes($_POST['head']);
    $foot = $myts->addSlashes($_POST['foot']);
    $themes = $myts->addSlashes($_POST['themes']);

    if (empty($nps_sn)) {
        $sql = 'insert into ' . $xoopsDB->prefix('tad_news_paper_setup') . " (title,head,foot,themes,status) values('{$title}','{$head}','{$foot}','{$themes}','1')";
    } else {
        $sql = 'update ' . $xoopsDB->prefix('tad_news_paper_setup') . " set title='{$title}',head='{$head}',foot='{$foot}',themes='{$themes}' where nps_sn='{$nps_sn}'";
    }

    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $nps_sn = (empty($nps_sn)) ? $xoopsDB->getInsertId() : $nps_sn;

    return $nps_sn;
}

//自動抓數字
function get_max_number($nps_sn = '')
{
    global $xoopsDB;
    $sql = 'select max(`number`) from ' . $xoopsDB->prefix('tad_news_paper') . " where nps_sn='{$nps_sn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($number) = $xoopsDB->fetchRow($result);
    $max_number = (empty($number)) ? 1 : $number + 1;

    return $max_number;
}

//選擇電子報內容
function add_newspaper($nps_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsTpl;

    $cates = get_all_news_cate();
    $myts = \MyTextSanitizer::getInstance();
    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('tad_news') . " WHERE enable='1' ORDER BY start_day DESC";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $opt = '';
    while (list($nsn, $ncsn, $news_title, $news_content, $start_day, $end_day, $enable, $uid, $passwd, $enable_group) = $xoopsDB->fetchRow($result)) {
        $news_title = $myts->htmlSpecialChars($news_title);
        $news_content = $myts->displayTarea($news_content, 1, 1, 1, 1, 0);
        $opt .= "<option value=\"$nsn\">[{$nsn}][ {$cates[$ncsn]} ] {$news_title}</option>";
    }

    $newspaper_set = get_newspaper_set($nps_sn);

    //自動抓數字
    $number = get_max_number($nps_sn);
    $xoopsTpl->assign('newspaper_set_title', $newspaper_set['title'] . sprintf(_MA_TADNEWS_NP_NUMBER_INPUT, "<input type='text' name='number' id='number' value='{$number}' style='width: 50px;'>"));
    $xoopsTpl->assign('opt', $opt);
    $xoopsTpl->assign('nps_sn', $nps_sn);

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new \XoopsFormHiddenToken();
    $xoopsTpl->assign('XOOPS_TOKEN', $token->render());
}

//儲存電子報內容
function save_newspaper()
{
    global $xoopsDB, $xoopsUser;
    //安全判斷
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
    }
    $all_news = mb_substr($_POST['all_news'], 1);

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    $sql = 'insert into ' . $xoopsDB->prefix('tad_news_paper') . " (`nps_sn`, `number`, `nsn_array`,`np_content`, `np_date`) values('{$_POST['nps_sn']}','{$_POST['number']}','{$all_news}','','$now')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $npsn = $xoopsDB->getInsertId();

    return $npsn;
}

//【步驟三】編輯電子報
function edit_newspaper($npsn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsModuleConfig, $xoopsTpl, $Tadnews;
    $cates = get_all_news_cate();
    $newspaper = get_newspaper($npsn);
    $newspaper_set = get_newspaper_set($newspaper['nps_sn']);
    $myts = \MyTextSanitizer::getInstance();

    if (empty($newspaper['np_content'])) {
        $html = '';
        if (empty($newspaper['nsn_array'])) {
            $news = 'limit 0,10';
        } else {
            $news = "where nsn in({$newspaper['nsn_array']}) order by find_in_set(nsn,'{$newspaper['nsn_array']}');";
        }
        $sql = 'select * from ' . $xoopsDB->prefix('tad_news') . " $news";

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (list($nsn, $ncsn, $news_title, $news_content, $start_day, $end_day, $enable, $uid, $passwd, $enable_group) = $xoopsDB->fetchRow($result)) {
            $news_title = $myts->htmlSpecialChars($news_title);
            $news_content = $myts->displayTarea($news_content, 1, 1, 1, 1, 0);
            $pic = $Tadnews->get_news_cover($ncsn, 'news_pic', $nsn, 'big', 'db', true, 'demo_cover_pic');
            if ($pic) {
                $img = "<img src='$pic' alt='{$news_title}' align='left' style='text-align:left; margin:0px 6px 6px 0px;max-width:300px;'>";
            } else {
                $img = '';
            }
            if (preg_match('/' . _SEPARTE2 . '/', $news_content)) {
                //支援xlanguage
                if (function_exists('xlanguage_ml')) {
                    $news_content = xlanguage_ml($news_content);
                }
                $news_content = str_replace('<p>' . _SEPARTE2 . '</p>', _SEPARTE2, $news_content);
                $content = explode(_SEPARTE2, $news_content);
            } else {
                $content = explode(_SEPARTE, $news_content);
            }

            $more = (empty($content[1])) ? '' : "<p><a href='" . XOOPS_URL . "/modules/tadnews/index.php?nsn={$nsn}' style='font-size: 0.9em;'>" . _TADNEWS_MORE . '...</a></p>';
            $news_content = $content[0] . $more;
            $html .= "
            <h3 class='TadNewsPaper_title'>" . _MA_TADNEWS_NP_TITLE_L . (string) ($cates[$ncsn]) . _MA_TADNEWS_NP_TITLE_R . "<a href='" . XOOPS_URL . "/modules/tadnews/index.php?nsn={$nsn}' target='_blank'>{$news_title}</a></h3>
            <div class='TadNewsPaper_content'>{$img}{$news_content}</div>
            <hr class='TadNewsPaper_hr'>";
        }
        //$html.="{$newspaper_set['foot']}";
    } else {
        $html = $newspaper['np_content'];
    }

    $CkEditor = new CkEditor('tadnews', 'np_content', $html);
    $CkEditor->setHeight(400);
    $editor = $CkEditor->render();

    $xoopsTpl->assign('editor', $editor);
    $xoopsTpl->assign('npsn', $npsn);
    $xoopsTpl->assign('np_title', $newspaper['np_title']);
}

//儲存所有內容
function save_all($npsn = '')
{
    global $xoopsDB, $xoopsUser;

    $myts = \MyTextSanitizer::getInstance();
    $np_content = $myts->addSlashes($_POST['np_content']);
    $np_title = $myts->addSlashes($_POST['np_title']);

    $sql = 'update ' . $xoopsDB->prefix('tad_news_paper') . " set np_content='{$np_content}',np_title='{$np_title}' where npsn='{$npsn}'";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $npsn;
}

//寄信表單
function sendmail_form($npsn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsTpl;
    $newspaper = get_newspaper($npsn);

    //取得郵寄名單
    $sql = 'select email from ' . $xoopsDB->prefix('tad_news_paper_email') . " where nps_sn='{$newspaper['nps_sn']}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $total = $xoopsDB->getRowsNum($result);

    while (list($email) = $xoopsDB->fetchRow($result)) {
        if (empty($email)) {
            continue;
        }

        $emailArr[] = $email;
    }

    //取得已寄名單
    $sql = 'select * from ' . $xoopsDB->prefix('tad_news_paper_send_log') . " where `npsn`='$npsn' order by send_time";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $npsn, $email, $send_time, $log
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $mailData[$email] = "$send_time ($log)";
    }

    $i = 1;

    $btn_xs = 4 == $_SESSION['bootstrap'] ? 'btn-sm' : 'btn-xs';
    foreach ($emailArr as $email) {
        $checked = empty($mailData[$email]) ? 'checked' : '';

        $data = empty($mailData[$email]) ? _MA_TADNEWS_NEVER_SEND . " <a href=\"javascript:delete_tad_news_email_func('$email');\" class='btn $btn_xs btn-danger'>" . _TADNEWS_DEL . '</a>' : $mailData[$email];

        $logdata[$i]['checkbox'] = "<input type='checkbox' name='mail_array[]' value='$email' $checked>";
        $logdata[$i]['email'] = $email;
        $logdata[$i]['data'] = $data;
        $i++;
    }

    $xoopsTpl->assign('npsn', $npsn);
    $xoopsTpl->assign('no_email', sprintf(_MA_TADNEWS_NO_EMAIL, $npsn));
    $xoopsTpl->assign('log', $logdata);
    $xoopsTpl->assign('total', sprintf(_MA_TADNEWS_MAIL_LIST, $total));
    $xoopsTpl->assign('np_content', $newspaper['np_content']);
    $xoopsTpl->assign('nps_sn', $newspaper['nps_sn']);
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new \XoopsFormHiddenToken();
    $xoopsTpl->assign('XOOPS_TOKEN', $token->render());

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_news_email_func', "newspaper.php?op=delete_tad_news_email_npsn&npsn={$npsn}&nps_sn={$newspaper['nps_sn']}&email=", 'email');
}

//立即寄出
function send_now($npsn = '')
{
    global $xoopsConfig, $xoopsUser, $xoopsDB;

    $mail_array = $_POST['mail_array'];

    $xoopsMailer = &getMailer();
    $xoopsMailer->multimailer->ContentType = 'text/html';
    $xoopsMailer->addHeaders('MIME-Version: 1.0');

    $newspaper = get_newspaper($npsn);
    $newspaper_set = get_newspaper_set($newspaper['nps_sn']);
    $subject = $newspaper_set['title'] . sprintf(_MA_TADNEWS_NP_NUMBER_INPUT, $newspaper['number']) . ' : ' . $newspaper['np_title'];

    $content = preview_newspaper($npsn);
    $content = str_replace('src="/', 'src="' . XOOPS_URL . '/', $content);

    $headers = '';

    // $xoopsMailer->setFromEmail($xoopsUser->getVar("email", "E"));
    // $xoopsMailer->setFromName($xoopsUser->getVar("uname", "E"));

    //$xoopsMailer->setSubject($subject);
    //$xoopsMailer->setBody($content);

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));

    foreach ($mail_array as $email) {
        if (empty($email)) {
            continue;
        }

        if ($xoopsMailer->sendMail($email, $subject, $content, $headers)) {
            $sql = 'replace into ' . $xoopsDB->prefix('tad_news_paper_send_log') . " (npsn,email,send_time,log) values('{$npsn}','{$email}','{$now}', 'success')";
            $xoopsDB->queryF($sql);
        } else {
            $sql = 'replace into ' . $xoopsDB->prefix('tad_news_paper_send_log') . " (npsn,email,send_time,log) values('{$npsn}','{$email}','{$now}','fail')";
            $xoopsDB->queryF($sql);
        }
    }
}

//刪除電子報
function del_newspaper($npsn = '')
{
    global $xoopsDB;

    $sql = 'delete from ' . $xoopsDB->prefix('tad_news_paper') . " where npsn='{$npsn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//刪除電子報設定組
function del_newspaper_set($nps_sn = '')
{
    global $xoopsDB;

    $sql = 'delete from ' . $xoopsDB->prefix('tad_news_paper_setup') . " where nps_sn='{$nps_sn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//Email 管理
function newspaper_email($nps_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig, $xoopsTpl;

    $np = get_newspaper_set($nps_sn);

    //取得郵寄名單
    $sql = 'select email,order_date from ' . $xoopsDB->prefix('tad_news_paper_email') . " where nps_sn='{$nps_sn}' order by email";

    $PageBar = Utility::getPageBar($sql, 30, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $memail = isset($_GET['memail']) ? htmlspecialchars($_GET['memail']) : '';

    $log = [];
    $i = 0;
    while (list($email, $order_date) = $xoopsDB->fetchRow($result)) {
        $email = htmlspecialchars($email);
        $ok = (check_email_mx($email)) ? 'ok' : "<span style='color:red;'>error</span>";

        $log[$i]['edit'] = $memail == $email ? true : false;
        $log[$i]['email'] = $email;
        $log[$i]['order_date'] = $order_date;
        $log[$i]['ok'] = $ok;
        $i++;
    }

    $xoopsTpl->assign('g2p', $_GET['g2p']);
    $xoopsTpl->assign('no_email', sprintf(_MA_TADNEWS_NO_EMAIL, $nps_sn));
    $xoopsTpl->assign('log', $log);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('nps_sn', $nps_sn);
    $xoopsTpl->assign('title', $np['title']);
    $xoopsTpl->assign('back', sprintf(_MA_TADNEWS_BACK_TO, $np['title']));
}

//檢查Email
function check_email_mx($email)
{
    if ((preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email))
        || (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/', $email))) {
        $host = explode('@', $email);
        if (checkdnsrr($host[1] . '.', 'MX')) {
            return true;
        }

        if (checkdnsrr($host[1] . '.', 'A')) {
            return true;
        }

        if (checkdnsrr($host[1] . '.', 'CNAME')) {
            return true;
        }
    }

    return false;
}

//刪除Email
function delete_tad_news_email($email = '', $nps_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;

    $email_part = mb_substr($email, 0, 15);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_news_paper_email') . " where email like '{$email_part}%' and nps_sn='{$nps_sn}'";

    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//更新Email
function update_email($old_email, $new_email, $nps_sn)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;

    $sql = 'update ' . $xoopsDB->prefix('tad_news_paper_email') . " set email='$new_email' where email='$old_email' and nps_sn='{$nps_sn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//匯入Email
function email_import($email_import = '', $nps_sn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;
    if (empty($email_import)) {
        return;
    }

    $email_import = str_replace("\n", '', $email_import);
    $email_import = str_replace(' ', '', $email_import);
    $emails = explode(',', $email_import);

    $now = date('Y-m-d H:i:s', xoops_getUserTimestamp(time()));
    foreach ($emails as $email) {
        $email = trim($email);
        $email = str_replace("'", '', $email);
        $email = str_replace('"', '', $email);

        if (!empty($email)) {
            $sql = 'replace into ' . $xoopsDB->prefix('tad_news_paper_email') . " (`nps_sn`,`email`,`order_date`) values('$nps_sn','$email','{$now}')";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }
}

//秀出郵寄結果
function sendmail_log($npsn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig, $xoopsTpl;

    $newspaper = get_newspaper($npsn);
    $np = get_newspaper_set($newspaper['nps_sn']);

    $sql = 'select * from ' . $xoopsDB->prefix('tad_news_paper_send_log') . " where `npsn`='$npsn' order by send_time";

    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $total = $xoopsDB->getRowsNum($result);
    $empty = false;
    if (empty($total)) {
        $empty = true;
    }

    $i = 0;
    while (false !== ($all = $xoopsDB->fetchArray($result))) {
        //以下會產生這些變數： $npsn, $email, $send_time, $log
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $main[$i]['email'] = $email;
        $main[$i]['send_time'] = $send_time;
        $main[$i]['log'] = $log;
        $i++;
    }
    $newspaper_title = $np['title'] . sprintf(_MA_TADNEWS_NP_NUMBER_INPUT, $newspaper['number']);
    $xoopsTpl->assign('title', $newspaper_title);
    $xoopsTpl->assign('no_email', sprintf(_MA_TADNEWS_NO_EMAIL, $npsn));
    $xoopsTpl->assign('log', $main);
    $xoopsTpl->assign('empty', $empty);
    $xoopsTpl->assign('nps_sn', $newspaper['nps_sn']);
    $xoopsTpl->assign('npsn', $npsn);
    $xoopsTpl->assign('back', sprintf(_MA_TADNEWS_BACK_TO, $newspaper_title));
}

/*-----------執行動作判斷區----------*/
require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$nps_sn = system_CleanVars($_REQUEST, 'nps_sn', 0, 'int');
$npsn = system_CleanVars($_REQUEST, 'npsn', 0, 'int');
$g2p = system_CleanVars($_REQUEST, 'g2p', 0, 'int');

$xoopsTpl->assign('op', $op);

switch ($op) {
    case 'save_newspaper_set':
        $nps_sn = save_newspaper_set($nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=add_newspaper&nps_sn={$nps_sn}");
        exit;

    //刪除電子報設定組
    case 'del_newspaper_set':
        del_newspaper_set($nps_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //編輯資料
    case 'add_newspaper':
        add_newspaper($nps_sn);
        break;
    case 'save_newspaper':
        $npsn = save_newspaper();
        header("location: {$_SERVER['PHP_SELF']}?op=edit_newspaper&npsn={$npsn}");
        exit;

    //編輯電子報資料
    case 'edit_newspaper':
        edit_newspaper($npsn);
        break;
    case 'save_all':
        save_all($npsn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail&npsn={$npsn}");
        exit;

    //刪除電子報
    case 'del_newspaper':
        del_newspaper($npsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case 'sendmail':
        sendmail_form($npsn);
        break;
    case 'send_now':
        send_now($npsn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail_log&npsn={$npsn}");
        exit;

    case 'sendmail_log':
        sendmail_log($npsn);
        break;
    case 'preview':
        $main = preview_newspaper($npsn);
        break;
    case 'modify':
        open_newspaper($nps_sn);
        break;
    case 'creat_newspaper':
        open_newspaper();
        break;
    case 'newspaper_email':
        newspaper_email($nps_sn);
        break;
    //刪除電子郵件
    case 'delete_tad_news_email':
        delete_tad_news_email($_GET['email'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn&g2p={$g2p}");
        exit;

    //刪除電子郵件
    case 'delete_tad_news_email_npsn':
        delete_tad_news_email($_GET['email'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=sendmail&npsn=$npsn");
        exit;

    //更新電子郵件
    case 'update_email':
        update_email($_POST['old_email'], $_POST['new_email'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn&g2p={$g2p}");
        exit;

    //匯入電子郵件
    case 'email_import':
        email_import($_POST['email_import'], $nps_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=newspaper_email&nps_sn=$nps_sn");
        exit;

    default:
        newspaper_set_table($nps_sn);
        break;
}

/*-----------秀出結果區--------------*/
if ('preview' === $op) {
    echo $main;
} else {
    require_once __DIR__ . '/footer.php';
}
