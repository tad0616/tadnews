<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\CategoryHelper;
use XoopsModules\Tadtools\SweetAlert2;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;

/**
 * 分類管理：分類 CRUD、樹狀走訪、分類間文章搬移與批次刪除
 * 由 admin/admin_function.php 搬入（2026-08-14），行為未變更。
 */
class Cate
{
    //列出所有tad_news資料（$kind="news","page"）
    public static function list_tad_news($the_ncsn = '0', $kind = 'news', $show_uid = '')
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
        Tools::chk_user_cate_power('pass');
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
        //沒指定分類時給空字串：樣板用 $ncsn!="" 判斷，PHP 8 起 0 != "" 為真會誤判成有選分類
        $xoopsTpl->assign('ncsn', empty($the_ncsn) ? '' : (int) $the_ncsn);
        $cate = $Tadnews->get_tad_news_cate($the_ncsn);
        $xoopsTpl->assign('cate', $cate);

        $SweetAlert2         = new SweetAlert2();
        $XOOPS_TOKEN_REQUEST = $GLOBALS['xoopsSecurity']->createToken();
        $SweetAlert2->setVar('method', 'post');
        $SweetAlert2->render('delete_tad_news_cate_func', "{$page}?op=delete_tad_news_cate&XOOPS_TOKEN_REQUEST={$XOOPS_TOKEN_REQUEST}&ncsn=", 'ncsn');
    }

    //列出所有tad_news_cate資料
    public static function list_tad_news_cate($of_ncsn = 0, $level = 0, $not_news = '0', $i = 0, $catearr = '')
    {
        global $xoopsDB;
        $old_level = $level;
        $left      = $level * 18 + 4;
        $level++;

        $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news` =? AND `of_ncsn` =? ORDER BY `sort`';
        $result = Utility::query($sql, 'si', [$not_news, $of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        //$catearr="";

        //$i=0;
        while (list($ncsn, $of_ncsn, $nc_title, $enable_group, $enable_post_group, $sort, $cate_pic, $not_news) = $xoopsDB->fetchRow($result)) {
            $sql2          = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `ncsn`=?';
            $result2       = Utility::query($sql2, 'i', [$ncsn]);
            list($counter) = $xoopsDB->fetchRow($result2);

            $pic    = (empty($cate_pic)) ? '../images/no_cover.png' : XOOPS_URL . "/uploads/tadnews/cate/{$cate_pic}";
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

            $sub = self::list_tad_news_cate($ncsn, $level, $not_news, $i, $catearr);
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
    public static function mk_thumb($ncsn = '', $col_name = '', $width = 480)
    {
        global $xoopsDB;
        require XOOPS_ROOT_PATH . '/modules/tadtools/upload/class.upload.php';

        if (file_exists(XOOPS_ROOT_PATH . "/uploads/tadnews/cate/{$ncsn}.png")) {
            unlink(XOOPS_ROOT_PATH . "/uploads/tadnews/cate/{$ncsn}.png");
        }

        $handle = new \Verot\Upload\Upload($_FILES[$col_name]);
        if ($handle->uploaded) {
            $handle->file_new_name_body = $ncsn;
            $handle->image_convert      = 'png';
            $handle->image_resize       = true;
            $handle->image_x            = $width;
            $handle->image_ratio_y      = true;
            $handle->file_overwrite     = true;
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
    public static function insert_tad_news_cate()
    {
        global $xoopsDB, $xoopsModuleConfig;
        Utility::xoops_security_check('', '', 'index.php');
        if (empty($_POST['enable_group']) or in_array('', $_POST['enable_group'])) {
            $enable_group = '';
        } else {
            $enable_group = implode(',', $_POST['enable_group']);
        }
        $enable_post_group = isset($_POST['enable_post_group']) ? implode(',', (Array) $_POST['enable_post_group']) : '1';
        $setup             = '';
        foreach ($_POST['setup'] as $key => $val) {
            $setup .= "{$key}=$val;";
        }
        $setup = mb_substr($setup, 0, -1);

        $of_ncsn  = (int) $_POST['of_ncsn'];
        $sort     = (int) $_POST['sort'];
        $not_news = (int) $_POST['not_news'];
        $nc_title = (string) $_POST['nc_title'];

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_cate') . '` (`of_ncsn`, `nc_title`, `enable_group`, `enable_post_group`, `sort`, `not_news`, `setup`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'isssiss', [$of_ncsn, $nc_title, $enable_group, $enable_post_group, $sort, $not_news, $setup]) or Utility::web_error($sql, __FILE__, __LINE__);

        //取得最後新增資料的流水編號
        $ncsn = $xoopsDB->getInsertId();

        if (!empty($_FILES['cate_pic'])) {
            self::mk_thumb($ncsn, 'cate_pic', $xoopsModuleConfig['cate_pic_width']);
        }

        return $ncsn;
    }

    //刪除tad_news_cate某筆資料資料
    public static function delete_tad_news_cate($ncsn = '')
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
    public static function change_kind($ncsn = '', $not_news = '')
    {
        global $xoopsDB;

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `not_news`=?, `of_ncsn`="0" WHERE `ncsn`=?';
        Utility::query($sql, 'si', [$not_news, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        //先找看看底下有無分類，若有將其也一起變
        $sub_cate = self::get_sub_cate($ncsn);
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
    public static function get_sub_cate($of_ncsn = '')
    {
        global $xoopsDB;
        $sql    = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn` = ?';
        $result = Utility::query($sql, 'i', [$of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($sub_ncsn) = $xoopsDB->fetchRow($result)) {
            $ccc = self::get_sub_cate($sub_ncsn);
            if (!empty($ccc)) {
                $aaa[] = $ccc;
            }

            $aaa[] = $sub_ncsn;
        }
        $bbb = implode(',', (Array) $aaa);
        //echo "<p style='color:red;'>$bbb</p>";

        return $bbb;
    }

    //搬移文章
    public static function move_to_cate($ncsn = '', $to_ncsn = '')
    {
        global $xoopsDB;

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `ncsn`=? WHERE `ncsn`=?';
        Utility::query($sql, 'ii', [$to_ncsn, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

    }

    //批次移動
    public static function move_news($nsn_arr = [], $ncsn = '')
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
    public static function del_news($nsn_arr = [])
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

    public static function tad_news_cate_form($ncsn = '', $not_news = '0')
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
        //setup 各項的預設值：新分類的 setup 是空的，樣板卻直接讀這些變數會噴 Undefined array key。
        //一律給空字串，與原本「變數未定義」時的判斷結果相同（'' != '0' 為真、'' == '1' 為假），畫面不變
        foreach (['only_title', 'title', 'tool', 'nav', 'breadcrumbs'] as $set_key) {
            $xoopsTpl->assign($set_key, '');
        }

        $setup_arr = explode(';', $setup);
        foreach ($setup_arr as $set) {
            //新分類或舊資料的 setup 可能是空字串或缺少 '='，直接 list() 會噴 Undefined array key 1
            if (false === mb_strpos($set, '=')) {
                continue;
            }
            list($set_name, $set_val) = explode('=', $set, 2);
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
        $xoopsTpl->assign('XOOPS_TOKEN', Utility::token_form('return'));
    }

    //更新tad_news_cate某一筆資料
    public static function update_tad_news_cate($ncsn = '')
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
            self::mk_thumb($ncsn, 'cate_pic', $xoopsModuleConfig['cate_pic_width']);
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

    /**
     * 產生分類樹（ztree）。新聞與單頁共用，差別僅在 not_news 篩選與連結目標。
     *
     * @param int|string $def_ncsn   目前選取的分類（高亮用）
     * @param int        $not_news   0=新聞樹，1=單頁樹
     * @param string     $page       連結目標入口檔
     * @param string     $tree_name  ztree 的 DOM 識別名
     */
    public static function cate_tree($def_ncsn = '', $not_news = 0, $page = 'main.php', $tree_name = 'news_tree')
    {
        global $xoopsDB, $xoopsTpl;

        $categoryHelper = new CategoryHelper('tad_news_cate', 'ncsn', 'of_ncsn', 'nc_title');
        $cate_count     = $categoryHelper->getCategoryCount('tad_news');

        $data[] = "{ id:0, pId:0, name:'" . _MA_TADNEWS_ALL_NEWS . "', url:'{$page}', target:'_self', open:true}";

        $sql    = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? ORDER BY `sort`';
        $result = Utility::query($sql, 's', [$not_news]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $nc_title = addslashes($nc_title);

            $font_style      = $def_ncsn == $ncsn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
            $display_counter = empty($cate_count[$ncsn]) ? '' : " ({$cate_count[$ncsn]})";
            $data[]          = "{ id:{$ncsn}, pId:{$of_ncsn}, name:'{$nc_title}{$display_counter}', url:'{$page}?ncsn={$ncsn}', open: true ,target:'_self' {$font_style}}";
        }

        $json = implode(",\n", (Array) $data);

        $Ztree = new Ztree($tree_name, $json, 'save_drag.php', 'save_cate_sort.php', 'of_ncsn', 'ncsn');
        $xoopsTpl->assign('ztree_code', $Ztree->render());

        return $data;
    }

    //新增資料到tad_news_cate中
    public static function add_tad_news_cate($title = '', $no_news = '0')
    {
        global $xoopsDB;
        Utility::xoops_security_check('', '', 'index.php');
        $enable_group = $enable_post_group = $setup = '';
        $sql          = 'SELECT MAX(`sort`) FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn`=?';
        $result       = Utility::query($sql, 's', ['']) or Utility::web_error($sql, __FILE__, __LINE__);

        list($sort) = $xoopsDB->fetchRow($result);
        $sort++;

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_news_cate') . '` (`of_ncsn`, `nc_title`, `enable_group`, `enable_post_group`, `sort`, `not_news`, `setup`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'isssiss', [0, $title, $enable_group, $enable_post_group, $sort, $no_news, $setup]) or redirect_header($_SERVER['PHP_SELF'], 3, _MD_TADNEWS_DB_ADD_ERROR1);

        //取得最後新增資料的流水編號
        $ncsn = $xoopsDB->getInsertId();

        return $ncsn;
    }

    //檢查目的地編號是否在其子目錄下
    public static function chk_cate_path($ncsn, $to_ncsn)
    {
        global $xoopsDB;
        //抓出子目錄的編號
        $sql    = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn` = ?';
        $result = Utility::query($sql, 'i', [$ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($sub_ncsn) = $xoopsDB->fetchRow($result)) {
            if (self::chk_cate_path($sub_ncsn, $to_ncsn)) {
                return true;
            }

            if ($sub_ncsn == $to_ncsn) {
                return true;
            }
        }

        return false;
    }

    //拖曳變更分類的父分類（AJAX：admin/save_drag.php）
    public static function save_drag($ncsn, $of_ncsn)
    {
        global $xoopsDB;

        if (self::chk_cate_path($ncsn, $of_ncsn)) {
            die(_MD_TADNEWS_CANT_MOVE_TO_SELF);
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `of_ncsn`=? WHERE `ncsn`=?';
        Utility::query($sql, 'ii', [$of_ncsn, $ncsn]) or die('Reset Fail! (' . date('Y-m-d H:i:s') . ')');
    }

    //拖曳調整分類排序（AJAX：admin/save_cate_sort.php）
    public static function save_cate_sort($ncsn, $sort)
    {
        global $xoopsDB;

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news_cate') . '` SET `sort`=? WHERE `ncsn`=?';
        Utility::query($sql, 'ii', [$sort, $ncsn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');
    }
}
