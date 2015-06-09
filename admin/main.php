<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadnews_adm_main.html';
include_once "header.php";
include_once "../function.php";
include_once "admin_function.php";

/*-----------function區--------------*/

//列出所有tad_news_cate資料
function list_tadnews_cate_tree($def_ncsn = "")
{
    global $xoopsDB, $xoopsTpl;

    $sql    = "select ncsn , count(*) from " . $xoopsDB->prefix("tad_news") . " group by ncsn";
    $result = $xoopsDB->query($sql);
    while (list($ncsn, $counter) = $xoopsDB->fetchRow($result)) {
        $cate_count[$ncsn] = $counter;
    }
    $path     = get_tadnews_cate_path($def_ncsn);
    $path_arr = array_keys($path);

    $sql    = "select ncsn,of_ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where not_news!='1' order by sort";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $font_style      = $def_ncsn == $ncsn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open            = in_array($ncsn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($cate_count[$ncsn]) ? "" : " ({$cate_count[$ncsn]})";
        $data[]          = "{ id:{$ncsn}, pId:{$of_ncsn}, name:'{$nc_title}{$display_counter}', url:'main.php?ncsn={$ncsn}', open: {$open} ,target:'_self' {$font_style}}";
    }

    $json = implode(",\n", $data);

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/ztree.php";
    $ztree      = new ztree("album_tree", $json, "save_drag.php", "save_cate_sort.php", "of_ncsn", "ncsn");
    $ztree_code = $ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);

    return $data;
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn     = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn      = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$show_uid = system_CleanVars($_REQUEST, 'show_uid', 0, 'int');

switch ($op) {

    //刪除資料
    case "delete_tad_news":
        $tadnews->delete_tad_news($nsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //批次管理
    case "batch":
        if ($_POST['act'] == "move_news") {
            move_news($_POST['nsn_arr'], $ncsn);
        } elseif ($_POST['act'] == "del_news") {
            del_news($_POST['nsn_arr']);
        }
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    default:
        list_tadnews_cate_tree($ncsn);
        list_tad_news($ncsn, "news", $show_uid);
        break;
}

/*-----------秀出結果區--------------*/
include_once "footer.php";
