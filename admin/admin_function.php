<?php
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;

//列出所有tad_news資料（$kind="news","page"）
function list_tad_news($the_ncsn = '0', $kind = 'news', $show_uid = '')
{
    global $xoopsModuleConfig, $Tadnews, $xoopsTpl;

    if (!empty($show_uid)) {
        $Tadnews->set_view_uid($show_uid);
    }

    $Tadnews->set_only_one_ncsn(true);
    $Tadnews->set_news_kind($kind);
    $Tadnews->set_summary(0);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_admin_tool(true);
    if (empty($the_ncsn) or 'news' === $kind) {
        $Tadnews->set_show_num($xoopsModuleConfig['show_num']);
    }
    $Tadnews->set_show_enable(0);
    //$Tadnews->set_news_cate_select(1);
    //$Tadnews->set_news_author_select(1);
    $Tadnews->set_news_check_mode(1);
    $Tadnews->chk_user_cate_power('pass');
    $options = $Tadnews->get_tad_news_cate_option(0, 0, '', true, '', '1');

    $page = 'main.php';
    if (!empty($the_ncsn)) {
        $Tadnews->set_view_ncsn($the_ncsn);
        if ('page' === $kind) {
            $Tadnews->set_sort_tool(1);
            $page = 'page.php';
        } else {
            $page = 'main.php';
        }
    }

    $Tadnews->get_news('assign');
    $xoopsTpl->assign('options', $options);
    $xoopsTpl->assign('ncsn', $the_ncsn);
    $cate = $Tadnews->get_tad_news_cate($the_ncsn);
    $xoopsTpl->assign('cate', $cate);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_news_cate_func', "{$page}?op=delete_tad_news_cate&ncsn=", 'ncsn');
}

//列出所有tad_news_cate資料
function list_tad_news_cate($of_ncsn = 0, $level = 0, $not_news = '0', $i = 0, $catearr = '')
{
    global $xoopsDB;
    $old_level = $level;
    $left = $level * 18 + 4;
    $level++;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news` =? AND `of_ncsn` =? ORDER BY `sort`';
    $result = Utility::query($sql, 'si', [$not_news, $of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    //$catearr="";

    //$i=0;
    while (list($ncsn, $of_ncsn, $nc_title, $enable_group, $enable_post_group, $sort, $cate_pic, $not_news) = $xoopsDB->fetchRow($result)) {
        $sql2 = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `ncsn`=?';
        $result2 = Utility::query($sql2, 'i', [$ncsn]);
        list($counter) = $xoopsDB->fetchRow($result2);

        $pic = (empty($cate_pic)) ? '../images/no_cover.png' : XOOPS_URL . "/uploads/tadnews/cate/{$cate_pic}";
        $g_txt = Utility::txt_to_group_name($enable_group, _TADNEWS_ALL_OK, ' , ');
        $gp_txt = Utility::txt_to_group_name($enable_post_group, _MA_TADNEWS_ONLY_ROOT, ' , ');

        $new_kind = ('1' == $not_news) ? 0 : 1;
        $change_text = ('1' == $not_news) ? _MA_TADNEWS_CHANGE_TO_NEWS : _MA_TADNEWS_CHANGE_TO_PAGE;

        $catearr[$i]['left'] = $left;
        $catearr[$i]['pic'] = $pic;
        $catearr[$i]['nc_title'] = $nc_title;
        $catearr[$i]['sort'] = $sort;
        $catearr[$i]['ncsn'] = $ncsn;
        $catearr[$i]['counter'] = $counter;
        $catearr[$i]['g_txt'] = $g_txt;
        $catearr[$i]['gp_txt'] = $gp_txt;
        $catearr[$i]['new_kind'] = $new_kind;
        $catearr[$i]['change_text'] = $change_text;
        $catearr[$i]['offset'] = empty($old_level) ? '' : "offset{$old_level}";

        $i++;

        $sub = list_tad_news_cate($ncsn, $level, $not_news, $i, $catearr);
        $i = $sub['i'];
        if (!empty($sub['arr'])) {
            $catearr = $sub['arr'];
        }
    }
    //$xoopsTpl->assign( "cate" , $catearr) ;
    $all['i'] = $i;
    $all['arr'] = $catearr;

    return $all;
}

//縮圖上傳
function mk_thumb($ncsn = '', $col_name = '', $width = 480)
{
    global $xoopsDB;
    require XOOPS_ROOT_PATH . '/modules/tadtools/upload/class.upload.php';

    if (file_exists(XOOPS_ROOT_PATH . "/uploads/tadnews/cate/{$ncsn}.png")) {
        unlink(XOOPS_ROOT_PATH . "/uploads/tadnews/cate/{$ncsn}.png");
    }

    $handle = new \Verot\Upload\Upload($_FILES[$col_name]);
    if ($handle->uploaded) {
        $handle->file_new_name_body = $ncsn;
        $handle->image_convert = 'png';
        $handle->image_resize = true;
        $handle->image_x = $width;
        $handle->image_ratio_y = true;
        $handle->file_overwrite = true;
        $handle->process(XOOPS_ROOT_PATH . '/uploads/tadnews/cate');
        $handle->auto_create_dir = true;
        if ($handle->processed) {
            $handle->clean();
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '`
            SET `cate_pic` = ?
            WHERE `ncsn` = ?';

            $params = ["{$ncsn}.png", $ncsn];
            Utility::query($sql, 'si', $params);

            return true;
        }
    }

    return false;
}

//新增資料到tad_news_cate中
function insert_tad_news_cate()
{
    global $xoopsDB, $xoopsModuleConfig;
    //安全判斷
    if ($_SERVER['SERVER_ADDR'] != '127.0.0.1' && !$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
    }
    if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
        $enable_group = '';
    } else {
        $enable_group = implode(',', $_POST['enable_group']);
    }
    $enable_post_group = isset($_POST['enable_post_group']) ? implode(',', $_POST['enable_post_group']) : '1';
    $setup = '';
    foreach ($_POST['setup'] as $key => $val) {
        $setup .= "{$key}=$val;";
    }
    $setup = mb_substr($setup, 0, -1);

    $of_ncsn = (int) $_POST['of_ncsn'];
    $sort = (int) $_POST['sort'];
    $not_news = (int) $_POST['not_news'];
    $nc_title = $_POST['nc_title'];

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_cate') . '` (`of_ncsn`, `nc_title`, `enable_group`, `enable_post_group`, `sort`, `not_news`, `setup`) VALUES (?, ?, ?, ?, ?, ?, ?)';
    Utility::query($sql, 'isssiss', [$of_ncsn, $nc_title, $enable_group, $enable_post_group, $sort, $not_news, $setup]) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $ncsn = $xoopsDB->getInsertId();

    if (!empty($_FILES['cate_pic'])) {
        mk_thumb($ncsn, 'cate_pic', $xoopsModuleConfig['cate_pic_width']);
    }

    return $ncsn;
}

//刪除tad_news_cate某筆資料資料
function delete_tad_news_cate($ncsn = '')
{
    global $xoopsDB, $Tadnews;

    $cate_org = $Tadnews->get_tad_news_cate($ncsn);

    //先找看看底下有無分類，若有將其父分類變成原分類之父分類
    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `of_ncsn` = ? WHERE `of_ncsn` = ?';
    Utility::query($sql, 'ii', [$cate_org['of_ncsn'], $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `ncsn` = ?';
    Utility::query($sql, 'i', [$ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

}

//轉換分類類型
function change_kind($ncsn = '', $not_news = '')
{
    global $xoopsDB;

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `not_news`=?, `of_ncsn`="0" WHERE `ncsn`=?';
    Utility::query($sql, 'si', [$not_news, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    //先找看看底下有無分類，若有將其也一起變
    $sub_cate = get_sub_cate($ncsn);
    if (!empty($sub_cate)) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `not_news`=? WHERE `ncsn` IN (?)';
        Utility::query($sql, 'ss', [$not_news, $sub_cate]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    if (1 == $not_news) {
        header('location: page.php');
        exit;
    }
    header('location: main.php');
    exit;
}

//找出底下的子分類
function get_sub_cate($of_ncsn = '')
{
    global $xoopsDB;
    $sql = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn` = ?';
    $result = Utility::query($sql, 'i', [$of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($sub_ncsn) = $xoopsDB->fetchRow($result)) {
        $ccc = get_sub_cate($sub_ncsn);
        if (!empty($ccc)) {
            $aaa[] = $ccc;
        }

        $aaa[] = $sub_ncsn;
    }
    $bbb = implode(',', $aaa);
    //echo "<p style='color:red;'>$bbb</p>";

    return $bbb;
}

//搬移文章
function move_to_cate($ncsn = '', $to_ncsn = '')
{
    global $xoopsDB;

    $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `ncsn`=? WHERE `ncsn`=?';
    Utility::query($sql, 'ii', [$to_ncsn, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

}

//批次移動
function move_news($nsn_arr = [], $ncsn = '')
{
    global $xoopsDB;
    if (empty($nsn_arr) or !is_array($nsn_arr)) {
        return;
    }

    foreach ($nsn_arr as $nsn) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `ncsn`=? WHERE `nsn`=?';
        Utility::query($sql, 'ii', [$ncsn, $nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    }
}

//批次刪除
function del_news($nsn_arr = [])
{
    global $xoopsDB, $Tadnews;
    if (empty($nsn_arr) or !is_array($nsn_arr)) {
        return;
    }

    foreach ($nsn_arr as $nsn) {
        $sql = 'DELETE FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `nsn`=?';
        Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        $Tadnews->delete_tad_news($nsn);
    }
}
