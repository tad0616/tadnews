<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadnews_adm_page.tpl';
include_once "header.php";
include_once "../function.php";
include_once "admin_function.php";

/*-----------function區--------------*/

//列出所有tad_news_cate資料
function list_tadnews_cate_tree($def_ncsn = "")
{
    global $xoopsDB, $xoopsTpl;

    $sql    = "SELECT ncsn , count(*) FROM " . $xoopsDB->prefix("tad_news") . " GROUP BY ncsn";
    $result = $xoopsDB->query($sql);
    while (list($ncsn, $counter) = $xoopsDB->fetchRow($result)) {
        $cate_count[$ncsn] = $counter;
    }
    $path     = get_tadnews_cate_path($def_ncsn);
    $path_arr = array_keys($path);

    $data[] = "{ id:0, pId:0, name:'All', url:'page.php', target:'_self', open:true}";

    $sql    = "SELECT ncsn,of_ncsn,nc_title FROM " . $xoopsDB->prefix("tad_news_cate") . " WHERE not_news='1' ORDER BY sort";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $font_style = $def_ncsn == $ncsn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        //$open            = in_array($ncsn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$ncsn]) ? "" : " ({$cate_count[$ncsn]})";
        $data[]          = "{ id:{$ncsn}, pId:{$of_ncsn}, name:'{$nc_title}{$display_counter}', url:'page.php?ncsn={$ncsn}', open: true ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php";
    $ztree      = new ztree("page_tree", $json, "save_drag.php", "save_cate_sort.php", "of_ncsn", "ncsn");
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}

function tad_news_cate_form($ncsn = "")
{
    global $xoopsDB, $xoopsTpl, $xoopsOption, $xoopsModuleConfig, $tadnews;
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

    //抓取預設值
    if (!empty($ncsn)) {
        $DBV = $tadnews->get_tad_news_cate($ncsn);
        $xoopsTpl->assign('cate', $DBV);
    } else {
        $DBV = array();
    }

    //預設值設定

    $ncsn              = (!isset($DBV['ncsn'])) ? $ncsn : $DBV['ncsn'];
    $of_ncsn           = (!isset($DBV['of_ncsn'])) ? "" : $DBV['of_ncsn'];
    $nc_title          = (!isset($DBV['nc_title'])) ? "" : $DBV['nc_title'];
    $sort              = (!isset($DBV['sort'])) ? $tadnews->get_max_sort() : $DBV['sort'];
    $enable_group      = (!isset($DBV['enable_group'])) ? "" : explode(",", $DBV['enable_group']);
    $enable_post_group = (!isset($DBV['enable_post_group'])) ? "" : explode(",", $DBV['enable_post_group']);
    $not_news          = (!isset($DBV['not_news'])) ? "" : $DBV['not_news'];
    $cate_pic          = (!isset($DBV['cate_pic'])) ? "" : $DBV['cate_pic'];
    $pic               = (empty($cate_pic)) ? "../images/no_cover.png" : _TADNEWS_CATE_URL . "/{$cate_pic}";
    $setup             = (!isset($DBV['setup'])) ? "" : $DBV['setup'];
    $setup_arr         = explode(';', $setup);
    foreach ($setup_arr as $set) {
        list($set_name, $set_val) = explode('=', $set);
        $xoopsTpl->assign($set_name, $set_val);
    }

    $cate_op = (empty($ncsn)) ? "insert_tad_news_cate" : "update_tad_news_cate";
    //$op="replace_tad_news_cate";

    $cate_select = $tadnews->get_tad_news_cate_option(0, 0, $of_ncsn, true, $ncsn, "1", "1");

    $SelectGroup_name = new XoopsFormSelectGroup("", "enable_group", false, $enable_group, 3, true);
    $SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_group = $SelectGroup_name->render();

    $SelectGroup_name = new XoopsFormSelectGroup("", "enable_post_group", false, $enable_post_group, 3, true);
    //$SelectGroup_name->addOption("", _TADNEWS_ALL_OK, false);
    $SelectGroup_name->setExtra("class='form-control'");
    $enable_post_group = $SelectGroup_name->render();

    $xoopsTpl->assign("cate_op", $cate_op);
    $cate_pic_width = $xoopsModuleConfig['cate_pic_width'] + 10;
    $xoopsTpl->assign("cate_pic_width", $cate_pic_width);
    $xoopsTpl->assign("cate_select", $cate_select);
    $xoopsTpl->assign("sort", $sort);
    $xoopsTpl->assign("ncsn", $ncsn);
    $xoopsTpl->assign("nc_title", $nc_title);
    $xoopsTpl->assign("enable_group", $enable_group);
    $xoopsTpl->assign("enable_post_group", $enable_post_group);
    $xoopsTpl->assign("pic", $pic);
    $xoopsTpl->assign("now_op", "tad_news_cate_form");
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $token = new XoopsFormHiddenToken('XOOPS_TOKEN', 360);
    $xoopsTpl->assign("XOOPS_TOKEN", $token->render());
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn     = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn      = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');
$to_ncsn  = system_CleanVars($_REQUEST, 'to_ncsn', 0, 'int');
$not_news = system_CleanVars($_REQUEST, 'not_news', 1, 'int');

switch ($op) {

    //刪除資料
    case "delete_tad_news":
        $tadnews->delete_tad_news($nsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;

    //批次管理
    case "batch":
        if ($_POST['act'] == "move_news") {
            move_news($_POST['nsn_arr'], $ncsn);
        } elseif ($_POST['act'] == "del_news") {
            del_news($_POST['nsn_arr']);
        }
        header("location: " . $_SERVER['PHP_SELF']);
        exit;

    case "add_news_cate":
        list_tadnews_cate_tree();
        tad_news_cate_form();
        break;

    case "modify_news_cate":
        list_tadnews_cate_tree($ncsn);
        tad_news_cate_form($ncsn);
        break;

    //新增資料
    case "insert_tad_news_cate":
        $ncsn = insert_tad_news_cate();
        header("location: " . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //更新資料
    case "update_tad_news_cate":
        update_tad_news_cate($ncsn);
        header("location: " . $_SERVER['PHP_SELF'] . "?ncsn=$ncsn");
        exit;

    //刪除資料
    case "delete_tad_news_cate":
        delete_tad_news_cate($ncsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;

    //搬移資料
    case "move_to":
        move_to_cate($ncsn, $to_ncsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;

    //分類類型互轉
    case "change_kind":
        change_kind($ncsn, $not_news);
        break;

    default:
        list_tadnews_cate_tree($ncsn);
        list_tad_news($ncsn, "page", $show_uid);
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('cate_img_url', _TADNEWS_CATE_URL);
$xoopsTpl->assign("jquery", get_jquery(true));
include_once "footer.php";
