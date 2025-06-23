<?php
use XoopsModules\Tadnews\Tadnews;
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/Tadtools/preloads/autoloader.php';
}

$Tadnews = new Tadnews();

//取得電子報設定資料
function get_newspaper_set($nps_sn = '')
{
    global $xoopsDB;
    $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_paper_setup') . '` WHERE `nps_sn`=?';
    $result = Utility::query($sql, 'i', [$nps_sn]) or Utility::web_error($sql, __FILE__, __LINE__);
    $data   = $xoopsDB->fetchArray($result);

    return $data;
}

//取得電子報資料
function get_newspaper($npsn = '')
{
    global $xoopsDB;
    $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_paper') . '` WHERE `npsn`=?';
    $result = Utility::query($sql, 'i', [$npsn]) or Utility::web_error($sql, __FILE__, __LINE__);

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

    $np     = get_newspaper($npsn);
    $sql    = 'SELECT `title`, `head`, `foot`, `themes` FROM `' . $xoopsDB->prefix('tad_news_paper_setup') . '` WHERE `nps_sn` =?';
    $result = Utility::query($sql, 'i', [$np['nps_sn']]) or Utility::web_error($sql, __FILE__, __LINE__);

    list($title, $head, $foot, $themes) = $xoopsDB->fetchRow($result);

    $myts             = \MyTextSanitizer::getInstance();
    $title            = $myts->htmlSpecialChars($title);
    $np['np_title']   = $myts->htmlSpecialChars($np['np_title']);
    $np['np_content'] = $myts->displayTarea($np['np_content'], 1, 1, 1, 1, 0);

    $head = str_replace('{N}', $np['number'], $head);
    $head = str_replace('{D}', mb_substr($np['np_date'], 0, 10), $head);
    $head = str_replace('{T}', $np['np_title'], $head);

    $filename = XOOPS_ROOT_PATH . "/uploads/tadnews/themes/{$themes}/index.html";
    // die('filename: ' . $filename);
    $handle   = fopen($filename, 'rb');
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
    global $xoopsTpl, $xoopsModuleConfig, $Tadnews, $tadnews_adm;
    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $ok_cat  = Tools::chk_user_cate_power('post');
    $ncsn    = (int) $ncsn;
    $isOwner = in_array($ncsn, $ok_cat) ? true : false;

    if (!$isOwner and !$tadnews_adm) {
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

    $ncsn              = (!isset($DBV['ncsn'])) ? $ncsn : $DBV['ncsn'];
    $of_ncsn           = (!isset($DBV['of_ncsn'])) ? '' : $DBV['of_ncsn'];
    $nc_title          = (!isset($DBV['nc_title'])) ? '' : $DBV['nc_title'];
    $sort              = (!isset($DBV['sort'])) ? $Tadnews->get_max_sort() : $DBV['sort'];
    $enable_group      = (!isset($DBV['enable_group'])) ? '' : explode(',', $DBV['enable_group']);
    $enable_post_group = (!isset($DBV['enable_post_group'])) ? '' : explode(',', $DBV['enable_post_group']);
    $not_news          = (!isset($DBV['not_news'])) ? $not_news : $DBV['not_news'];
    $cate_pic          = (!isset($DBV['cate_pic'])) ? '' : $DBV['cate_pic'];
    $pic               = (empty($cate_pic)) ? '../images/no_cover.png' : XOOPS_URL . "/uploads/tadnews/cate/{$cate_pic}";
    $setup             = (!isset($DBV['setup'])) ? '' : $DBV['setup'];
    $setup_arr         = explode(';', $setup);
    foreach ($setup_arr as $set) {
        list($set_name, $set_val) = explode('=', $set);
        $xoopsTpl->assign($set_name, $set_val);
    }

    $cate_op = (empty($ncsn)) ? 'insert_tad_news_cate' : 'update_tad_news_cate';
    //$op="replace_tad_news_cate";

    $cate_select = $Tadnews->get_tad_news_cate_option(0, 0, $of_ncsn, true, $ncsn, '1', $not_news);

    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_group', false, $enable_group, 5, true);
    $SelectGroup_name->addOption('', _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_group = $SelectGroup_name->render();

    $SelectGroup_name = new \XoopsFormSelectGroup('', 'enable_post_group', false, $enable_post_group, 5, true);
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
    global $xoopsDB, $xoopsModuleConfig, $tadnews_adm;

    $ok_cat  = Tools::chk_user_cate_power('post');
    $ncsn    = (int) $ncsn;
    $isOwner = in_array($ncsn, $ok_cat) ? true : false;

    if (!$isOwner and !$tadnews_adm) {
        redirect_header('index.php', 3, _TADNEWS_NO_ADMIN_POWER . '<br>' . __FILE__ . ':' . __LINE__);
    }

    if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
        $enable_group = '';
    } else {
        $enable_group = implode(',', (Array) $_POST['enable_group']);
    }
    $enable_post_group = implode(',', (Array) $_POST['enable_post_group']);
    $setup             = '';
    foreach ($_POST['setup'] as $key => $val) {
        $setup .= "{$key}=$val;";
    }
    $setup = mb_substr($setup, 0, -1);

    $of_ncsn  = (int) $_POST['of_ncsn'];
    $not_news = (int) $_POST['not_news'];
    $nc_title = (string) $_POST['nc_title'];

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `of_ncsn`=?, `nc_title`=?, `enable_group`=?, `enable_post_group`=?, `not_news`=?, `setup`=? WHERE `ncsn`=?';
    Utility::query($sql, 'isssssi', [$of_ncsn, $nc_title, $enable_group, $enable_post_group, $not_news, $setup, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    if (!empty($_FILES['cate_pic']['name'])) {
        mk_thumb($ncsn, 'cate_pic', $xoopsModuleConfig['cate_pic_width']);
    }

    $moduleHandler   = xoops_getHandler('module');
    $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
    if ($TadThemesModule) {
        $sql    = 'SELECT `menuid` FROM `' . $xoopsDB->prefix('tad_themes_menu') . '` WHERE `link_cate_name`=? AND `link_cate_sn`=?';
        $result = Utility::query($sql, 'si', ['tadnews_page_cate', $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        $RowsNum = $xoopsDB->getRowsNum($result);
        if ($RowsNum > 0) {
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_themes_menu') . '` SET `itemname`=? WHERE `link_cate_name`=\'tadnews_page_cate\' AND `link_cate_sn`=?';
            Utility::query($sql, 'si', [$nc_title, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        }
    }

    return $ncsn;
}
