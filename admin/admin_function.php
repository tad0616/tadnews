<?php
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;

//列出所有tad_news資料（$kind="news","page"）
function list_tad_news($the_ncsn = '0', $kind = 'news', $show_uid = '')
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption, $xoopsModuleConfig, $tadnews, $xoopsTpl;

    if (!empty($show_uid)) {
        $tadnews->set_view_uid($show_uid);
    }

    $tadnews->set_only_one_ncsn(true);
    $tadnews->set_news_kind($kind);
    $tadnews->set_summary(0);
    $tadnews->set_show_mode('list');
    $tadnews->set_admin_tool(true);
    if (empty($the_ncsn) or 'news' === $kind) {
        $tadnews->set_show_num($xoopsModuleConfig['show_num']);
    }
    $tadnews->set_show_enable(0);
    //$tadnews->set_news_cate_select(1);
    //$tadnews->set_news_author_select(1);
    $tadnews->set_news_check_mode(1);
    $tadnews->chk_user_cate_power('pass');
    $options = $tadnews->get_tad_news_cate_option(0, 0, '', true, '', '1');

    if (!empty($the_ncsn)) {
        $tadnews->set_view_ncsn($the_ncsn);
        if ('page' === $kind) {
            $tadnews->set_sort_tool(1);
            $page = 'page.php';
        } else {
            $page = 'main.php';
        }
    }

    $tadnews->get_news('assign');
    $xoopsTpl->assign('options', $options);
    $xoopsTpl->assign('ncsn', $the_ncsn);
    $cate = $tadnews->get_tad_news_cate($the_ncsn);
    $xoopsTpl->assign('cate', $cate);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_news_cate_func', "{$page}?op=delete_tad_news_cate&ncsn=", 'ncsn');
}

//列出所有tad_news_cate資料
function list_tad_news_cate($of_ncsn = 0, $level = 0, $not_news = '0', $i = 0, $catearr = '')
{
    global $xoopsDB, $xoopsModule, $xoopsTpl, $tadnews;
    $old_level = $level;
    $left      = $level * 18 + 4;
    $level++;

    $sql    = 'select * from ' . $xoopsDB->prefix('tad_news_cate') . " where not_news='{$not_news}' and of_ncsn='{$of_ncsn}' order by sort";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //$catearr="";

    //$i=0;
    while (list($ncsn, $of_ncsn, $nc_title, $enable_group, $enable_post_group, $sort, $cate_pic, $not_news) = $xoopsDB->fetchRow($result)) {
        $sql2          = 'select count(*) from ' . $xoopsDB->prefix('tad_news') . " where ncsn='{$ncsn}'";
        $result2       = $xoopsDB->query($sql2);
        list($counter) = $xoopsDB->fetchRow($result2);

        $pic    = (empty($cate_pic)) ? '../images/no_cover.png' : _TADNEWS_CATE_URL . "/{$cate_pic}";
        $g_txt  = Utility::txt_to_group_name($enable_group, _TADNEWS_ALL_OK, ' , ');
        $gp_txt = Utility::txt_to_group_name($enable_post_group, _MA_TADNEWS_ONLY_ROOT, ' , ');

        $new_kind    = ('1' == $not_news) ? 0 : 1;
        $change_text = ('1' == $not_news) ? _MA_TADNEWS_CHANGE_TO_NEWS : _MA_TADNEWS_CHANGE_TO_PAGE;

        $catearr[$i]['left']        = $left;
        $catearr[$i]['pic']         = $pic;
        $catearr[$i]['nc_title']    = $nc_title;
        $catearr[$i]['sort']        = $sort;
        $catearr[$i]['ncsn']        = $ncsn;
        $catearr[$i]['counter']     = $counter;
        $catearr[$i]['g_txt']       = $g_txt;
        $catearr[$i]['gp_txt']      = $gp_txt;
        $catearr[$i]['new_kind']    = $new_kind;
        $catearr[$i]['change_text'] = $change_text;
        $catearr[$i]['offset']      = empty($old_level) ? '' : "offset{$old_level}";

        $i++;

        $sub = list_tad_news_cate($ncsn, $level, $not_news, $i, $catearr);
        $i   = $sub['i'];
        if (!empty($sub['arr'])) {
            $catearr = $sub['arr'];
        }
    }
    //$xoopsTpl->assign( "cate" , $catearr) ;
    $all['i']   = $i;
    $all['arr'] = $catearr;

    return $all;
}

//縮圖上傳
function mk_thumb($ncsn = '', $col_name = '', $width = 100)
{
    global $xoopsDB;
    include XOOPS_ROOT_PATH . '/modules/tadtools/upload/class.upload.php';

    if (file_exists(_TADNEWS_CATE_DIR . "/{$ncsn}.png")) {
        unlink(_TADNEWS_CATE_DIR . "/{$ncsn}.png");
    }
    //die(_TADNEWS_CATE_DIR);
    $handle = new upload($_FILES[$col_name]);
    if ($handle->uploaded) {
        $handle->file_new_name_body = $ncsn;
        $handle->image_convert      = 'png';
        $handle->image_resize       = true;
        $handle->image_x            = $width;
        $handle->image_ratio_y      = true;
        $handle->file_overwrite     = true;
        $handle->process(_TADNEWS_CATE_DIR);
        $handle->auto_create_dir = true;
        if ($handle->processed) {
            $handle->clean();
            $sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . " set  cate_pic = '{$ncsn}.png' where ncsn='$ncsn'";
            $xoopsDB->queryF($sql);

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
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode('<br>', $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header('index.php', 3, $error);
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

    $myts              = MyTextSanitizer::getInstance();
    $of_ncsn           = (int) $_POST['of_ncsn'];
    $sort              = (int) $_POST['sort'];
    $not_news          = (int) $_POST['not_news'];
    $nc_title          = $myts->addSlashes($_POST['nc_title']);
    $enable_post_group = $myts->addSlashes($enable_post_group);
    $enable_group      = $myts->addSlashes($enable_group);
    $setup             = $myts->addSlashes($setup);

    $sql = 'insert into ' . $xoopsDB->prefix('tad_news_cate') . " (of_ncsn,nc_title,enable_group,enable_post_group,sort,not_news,setup) values('{$of_ncsn}','{$nc_title}','{$enable_group}','{$enable_post_group}','{$sort}','{$not_news}','{$setup}')";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    global $xoopsDB, $tadnews;

    $cate_org = $tadnews->get_tad_news_cate($ncsn);

    //先找看看底下有無分類，若有將其父分類變成原分類之父分類
    $sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . "  set  of_ncsn = '{$cate_org['of_ncsn']}' where of_ncsn='$ncsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $sql = 'delete from ' . $xoopsDB->prefix('tad_news_cate') . " where ncsn='$ncsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//轉換分類類型
function change_kind($ncsn = '', $not_news = '')
{
    global $xoopsDB, $xoopsModuleConfig;

    $sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . " set not_news='{$not_news}' , of_ncsn='0' where ncsn ='{$ncsn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //先找看看底下有無分類，若有將其也一起變
    $sub_cate = get_sub_cate($ncsn);
    if (!empty($sub_cate)) {
        $sql = 'update ' . $xoopsDB->prefix('tad_news_cate') . " set not_news='{$not_news}' where ncsn in ($sub_cate)";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
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
    $sql    = 'select ncsn from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='$of_ncsn'";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    //echo "<p>$sql</p>";
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

    $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set ncsn='{$to_ncsn}' where ncsn='{$ncsn}'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
}

//批次移動
function move_news($nsn_arr = [], $ncsn = '')
{
    global $xoopsDB;
    if (empty($nsn_arr) or !is_array($nsn_arr)) {
        return;
    }

    foreach ($nsn_arr as $nsn) {
        $sql = 'update ' . $xoopsDB->prefix('tad_news') . " set ncsn='{$ncsn}' where nsn='{$nsn}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    }
}

//批次刪除
function del_news($nsn_arr = [])
{
    global $xoopsDB, $tadnews;
    if (empty($nsn_arr) or !is_array($nsn_arr)) {
        return;
    }

    foreach ($nsn_arr as $nsn) {
        $sql = 'delete from ' . $xoopsDB->prefix('tad_news') . " where nsn='{$nsn}'";
        $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);
        $tadnews->delete_tad_news($nsn);
    }
}
