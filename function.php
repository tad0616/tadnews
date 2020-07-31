<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/Tadtools/preloads/autoloader.php';
}

$Tadnews = new Tadnews();
require_once __DIR__ . '/block_function.php';

//取得路徑
function get_tadnews_cate_path($the_ncsn = '', $include_self = true)
{
    global $xoopsDB, $Tadnews;

    $arr[0]['ncsn'] = '';
    $arr[0]['nc_title'] = "<i class='fa fa-home'></i>";
    $arr[0]['sub'] = get_tadnews_sub_cate(0);
    if (!empty($the_ncsn)) {
        $tbl = $xoopsDB->prefix('tad_news_cate');
        $sql = "SELECT t1.ncsn AS lev1, t2.ncsn as lev2, t3.ncsn as lev3, t4.ncsn as lev4, t5.ncsn as lev5, t6.ncsn as lev6, t7.ncsn as lev7
            FROM `{$tbl}` t1
            LEFT JOIN `{$tbl}` t2 ON t2.of_ncsn = t1.ncsn
            LEFT JOIN `{$tbl}` t3 ON t3.of_ncsn = t2.ncsn
            LEFT JOIN `{$tbl}` t4 ON t4.of_ncsn = t3.ncsn
            LEFT JOIN `{$tbl}` t5 ON t5.of_ncsn = t4.ncsn
            LEFT JOIN `{$tbl}` t6 ON t6.of_ncsn = t5.ncsn
            LEFT JOIN `{$tbl}` t7 ON t7.of_ncsn = t6.ncsn
            WHERE t1.of_ncsn = '0'";

        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        while (false !== ($all = $xoopsDB->fetchArray($result))) {
            if (in_array($the_ncsn, $all)) {
                foreach ($all as $ncsn) {
                    if (!empty($ncsn)) {
                        if (!$include_self and $ncsn == $the_ncsn) {
                            break;
                        }
                        $arr[$ncsn] = $Tadnews->get_tad_news_cate($ncsn);
                        $arr[$ncsn]['sub'] = get_tadnews_sub_cate($ncsn);
                        // die(var_dump(get_tadnews_sub_cate($ncsn)));
                        if ($ncsn == $the_ncsn) {
                            break;
                        }
                    }
                }
                //$main.="<br>";
                break;
            }
        }
    }

    return $arr;
}

function get_tadnews_sub_cate($ncsn = '0')
{
    global $xoopsDB;
    $sql = 'select ncsn,nc_title from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='{$ncsn}'";
    // die($sql);
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $ncsn_arr = [];
    while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $ncsn_arr[$ncsn] = $nc_title;
    }
    // die(var_dump($ncsn_arr));
    return $ncsn_arr;
}

//取得電子報設定資料
function get_newspaper_set($nps_sn = '')
{
    global $xoopsDB;
    $sql = 'select * from `' . $xoopsDB->prefix('tad_news_paper_setup') . "` where `nps_sn`='{$nps_sn}'";
    $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//取得電子報資料
function get_newspaper($npsn = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;
    $sql = 'select * from ' . $xoopsDB->prefix('tad_news_paper') . " where npsn='{$npsn}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $data = $xoopsDB->fetchArray($result);

    return $data;
}

//預覽電子報
function preview_newspaper($npsn = '')
{
    global $xoopsDB;
    if (empty($npsn)) {
        return;
    }

    $np = get_newspaper($npsn);
    $sql = 'select title,head,foot,themes from ' . $xoopsDB->prefix('tad_news_paper_setup') . " where nps_sn='{$np['nps_sn']}'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    list($title, $head, $foot, $themes) = $xoopsDB->fetchRow($result);

    $myts = \MyTextSanitizer::getInstance();
    $title = $myts->htmlSpecialChars($title);
    $np['np_title'] = $myts->htmlSpecialChars($np['np_title']);
    $np['np_content'] = $myts->displayTarea($np['np_content'], 1, 1, 1, 1, 0);

    $head = str_replace('{N}', $np['number'], $head);
    $head = str_replace('{D}', mb_substr($np['np_date'], 0, 10), $head);
    $head = str_replace('{T}', $np['np_title'], $head);

    $filename = XOOPS_ROOT_PATH . "/uploads/tadnews/themes/{$themes}/index.html";
    // die('filename: ' . $filename);
    $handle = fopen($filename, 'rb');
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);
    $main = str_replace('{TNP_THEME}', XOOPS_URL . "/uploads/tadnews/themes/{$themes}/", $contents);
    $main = str_replace('{TNP_CSS}', '', $main);
    $main = str_replace('{TNP_TITLE}', $title, $main);
    $char = _CHARSET;
    $main = str_replace('{TNP_CODE}', $char, $main);
    $main = str_replace('{TNP_HEAD}', $head, $main);
    $main = str_replace('{TNP_FOOT}', $foot, $main);
    $main = str_replace('{TNP_URL}', XOOPS_URL . "/modules/tadnews/newspaper.php?npsn={$npsn}", $main);
    $main = str_replace('{TNP_CONTENT}', $np['np_content'], $main);

    return $main;
}

function tad_news_cate_form($ncsn = '', $not_news = '0')
{
    global $xoopsDB, $xoopsTpl, $xoopsOption, $xoopsModuleConfig, $Tadnews, $isAdmin;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $ok_cat = $Tadnews->chk_user_cate_power('post');
    $ncsn = (int) $ncsn;
    $isOwner = in_array($ncsn, $ok_cat) ? true : false;

    if (!$isOwner and !$isAdmin) {
        redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
    }

    //抓取預設值
    if (!empty($ncsn)) {
        $DBV = $Tadnews->get_tad_news_cate($ncsn);
        $xoopsTpl->assign('cate', $DBV);
    } else {
        $DBV = [];
    }

    //預設值設定

    $ncsn = (!isset($DBV['ncsn'])) ? $ncsn : $DBV['ncsn'];
    $of_ncsn = (!isset($DBV['of_ncsn'])) ? '' : $DBV['of_ncsn'];
    $nc_title = (!isset($DBV['nc_title'])) ? '' : $DBV['nc_title'];
    $sort = (!isset($DBV['sort'])) ? $Tadnews->get_max_sort() : $DBV['sort'];
    $enable_group = (!isset($DBV['enable_group'])) ? '' : explode(',', $DBV['enable_group']);
    $enable_post_group = (!isset($DBV['enable_post_group'])) ? '' : explode(',', $DBV['enable_post_group']);
    $not_news = (!isset($DBV['not_news'])) ? $not_news : $DBV['not_news'];
    $cate_pic = (!isset($DBV['cate_pic'])) ? '' : $DBV['cate_pic'];
    $pic = (empty($cate_pic)) ? '../images/no_cover.png' : XOOPS_URL . "/uploads/tadnews/cate/{$cate_pic}";
    $setup = (!isset($DBV['setup'])) ? '' : $DBV['setup'];
    $setup_arr = explode(';', $setup);
    foreach ($setup_arr as $set) {
        list($set_name, $set_val) = explode('=', $set);
        $xoopsTpl->assign($set_name, $set_val);
    }

    $cate_op = (empty($ncsn)) ? 'insert_tad_news_cate' : 'update_tad_news_cate';
    //$op="replace_tad_news_cate";

    $cate_select = $Tadnews->get_tad_news_cate_option(0, 0, $of_ncsn, true, $ncsn, '1', $not_news);

    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_group', false, $enable_group, 3, true);
    $SelectGroup_name->addOption('', _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_group = $SelectGroup_name->render();

    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_post_group', false, $enable_post_group, 3, true);
    //$SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_post_group = $SelectGroup_name->render();

    $xoopsTpl->assign('cate_op', $cate_op);
    $cate_pic_width = $xoopsModuleConfig['cate_pic_width'] + 10;
    $xoopsTpl->assign('cate_pic_width', $cate_pic_width);
    $xoopsTpl->assign('jquery', Utility::get_jquery(true));
    $xoopsTpl->assign('cate_select', $cate_select);
    $xoopsTpl->assign('sort', $sort);
    $xoopsTpl->assign('ncsn', $ncsn);
    $xoopsTpl->assign('nc_title', $nc_title);
    $xoopsTpl->assign('enable_group', $enable_group);
    $xoopsTpl->assign('enable_post_group', $enable_post_group);
    $xoopsTpl->assign('pic', $pic);
    $xoopsTpl->assign('now_op', 'tad_news_cate_form');
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
    $token = new \XoopsFormHiddenToken();
    $xoopsTpl->assign('XOOPS_TOKEN', $token->render());
}

//更新tad_news_cate某一筆資料
function update_tad_news_cate($ncsn = '')
{
    global $xoopsDB, $xoopsModuleConfig, $Tadnews, $isAdmin;

    $ok_cat = $Tadnews->chk_user_cate_power('post');
    $ncsn = (int) $ncsn;
    $isOwner = in_array($ncsn, $ok_cat) ? true : false;

    if (!$isOwner and !$isAdmin) {
        redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
    }

    if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
        $enable_group = '';
    } else {
        $enable_group = implode(',', $_POST['enable_group']);
    }
    $enable_post_group = implode(',', $_POST['enable_post_group']);

    foreach ($_POST['setup'] as $key => $val) {
        $setup .= "{$key}=$val;";
    }
    $setup = mb_substr($setup, 0, -1);

    $myts = \MyTextSanitizer::getInstance();
    $of_ncsn = (int) $_POST['of_ncsn'];
    $sort = (int) $_POST['sort'];
    $not_news = (int) $_POST['not_news'];
    $nc_title = $myts->addSlashes($_POST['nc_title']);
    $enable_post_group = $myts->addSlashes($enable_post_group);
    $enable_group = $myts->addSlashes($enable_group);
    $setup = $myts->addSlashes($setup);

    $sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . " set  of_ncsn = '{$of_ncsn}', nc_title = '{$nc_title}', enable_group = '{$enable_group}', enable_post_group = '{$enable_post_group}',not_news='{$not_news}',setup='{$setup}' where ncsn='$ncsn'";
    // die($sql);
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    if (!empty($_FILES['cate_pic']['name'])) {
        mk_thumb($ncsn, 'cate_pic', $xoopsModuleConfig['cate_pic_width']);
    }

    $moduleHandler = xoops_getHandler('module');
    $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql = 'select menuid from ' . $xoopsDB->prefix('tad_themes_menu') . " where `link_cate_name`='tadnews_page_cate' and `link_cate_sn`='{$ncsn}'";
        $result = $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $RowsNum = $xoopsDB->getRowsNum($result);
        if ($RowsNum > 0) {
            $sql = 'update ' . $xoopsDB->prefix('tad_themes_menu') . " set `itemname`='{$nc_title}' where `link_cate_name`='tadnews_page_cate' and `link_cate_sn`='{$ncsn}'";
            $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        }
    }

    return $ncsn;
}
