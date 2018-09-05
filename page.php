<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
/*-----------function區--------------*/

//顯示單一新聞
function show_news($nsn = "")
{
    global $xoopsModuleConfig, $xoopsTpl, $interface_menu, $tadnews;

    $tadnews->set_view_nsn($nsn);
    $tadnews->set_news_kind("page");
    $tadnews->set_cover(true, "db");
    $tadnews->set_summary('full');
    $tadnews->get_news();

}

//列出所有tad_news資料
function list_tad_all_news($the_ncsn = "")
{
    global $xoopsTpl, $xoopsDB, $tadnews;

    $tadnews->set_news_kind("page");
    $tadnews->set_show_num('none');
    $tadnews->set_view_ncsn($the_ncsn);
    $tadnews->get_cate_news();

    $RowsNum         = 0;
    $modhandler      = xoops_getHandler('module');
    $TadThemesModule = $modhandler->getByDirname("tad_themes");
    if ($TadThemesModule) {
        $sql     = "select menuid from " . $xoopsDB->prefix("tad_themes_menu") . " where `link_cate_name`='tadnews_page_cate' and `link_cate_sn`='{$the_ncsn}'";
        $result  = $xoopsDB->queryF($sql) or web_error($sql);
        $RowsNum = $xoopsDB->getRowsNum($result);
    }
    if ($RowsNum > 0) {
        $xoopsTpl->assign('show_add_to_menu', 0);
    } else {
        $xoopsTpl->assign('show_add_to_menu', 1);
    }
    get_jquery(true);
}

function add_to_menu($ncsn = "")
{
    global $xoopsDB;

    $sql    = "select * from " . $xoopsDB->prefix("tad_news_cate") . " where ncsn='$ncsn'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    $cate   = $xoopsDB->fetchArray($result);

    $modhandler      = xoops_getHandler('module');
    $TadThemesModule = $modhandler->getByDirname("tad_themes");
    if ($TadThemesModule) {
        $sql = "insert into " . $xoopsDB->prefix("tad_themes_menu") . " (`of_level`,`position`,`itemname`,`itemurl`,`membersonly`,`status`,`mainmenu`,`target`,`icon`, `link_cate_name` ,`link_cate_sn`, `read_group`) values('0','1','{$cate['nc_title']}','" . XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}','0','1','0','_self','fa-angle-right', 'tadnews_page_cate','{$ncsn}', '1,2,3')";
        $xoopsDB->queryF($sql) or web_error($sql);
        //取得最後新增資料的流水編號
        $menuid = $xoopsDB->getInsertId();
    }
}

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op   = system_CleanVars($_REQUEST, 'op', '', 'string');
$ncsn = system_CleanVars($_REQUEST, 'ncsn', 0, 'int');
$nsn  = system_CleanVars($_REQUEST, 'nsn', 0, 'int');
$fsn  = system_CleanVars($_REQUEST, 'fsn', 0, 'int');

switch ($op) {

    //下載檔案
    case "tufdl":
        $files_sn = isset($_GET['files_sn']) ? (int) $_GET['files_sn'] : "";
        $TadUpFiles->add_file_counter($files_sn, $hash = false);
        exit;
        break;

    //刪除資料
    case "delete_tad_news":
        $tadnews->delete_tad_news($nsn);
        header("location: " . $_SERVER['PHP_SELF']);
        exit;
        break;

    //新增至選單
    case "add_to_menu":
        add_to_menu($ncsn);
        header("location: {$_SERVER['PHP_SELF']}?ncsn=$ncsn");
        exit;
        break;

    default:
        if (!empty($nsn)) {
            $xoopsOption['template_main'] = "tadnews_page.tpl";
            include XOOPS_ROOT_PATH . "/header.php";
            show_news($nsn);
        } elseif (!empty($ncsn)) {
            $xoopsOption['template_main'] = "tadnews_page_list.tpl";
            include XOOPS_ROOT_PATH . "/header.php";
            list_tad_all_news($ncsn);
        } else {
            header("location:index.php?nsn={$nsn}");
            exit;
        }

        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("isAdmin", $isAdmin);
include_once XOOPS_ROOT_PATH . '/include/comment_view.php';
include_once XOOPS_ROOT_PATH . '/footer.php';
