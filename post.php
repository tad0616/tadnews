<?php
use Xmf\Request;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$GLOBALS['xoopsOption']['template_main'] = 'tadnews_index.tpl';
require_once __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';
/*-----------function區--------------*/
if (empty($xoopsUser)) {
    redirect_header('index.php', 3, _MD_TADNEWS_NO_POST_POWER);
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$nsn = Request::getInt('nsn');
$ncsn = Request::getInt('ncsn');
$sort = Request::getInt('sort');

switch ($op) {
    //新增資料
    case 'insert_tad_news':
        $Tadnews->insert_tad_news();
        break;

    //輸入表格
    case 'tad_news_form':
        $Tadnews->tad_news_form($nsn);
        break;

    //更新資料
    case 'update_tad_news':
        $Tadnews->update_tad_news($nsn);
        break;

    //啟用文章
    case 'enable_news':
        $Tadnews->enable_tad_news($nsn);
        break;

    //刪除頁籤
    case 'del_page_tab':
        $Tadnews->del_page_tab($nsn, $sort);
        header("location:post.php?op=tad_news_form&nsn=$nsn");
        exit;

    //刪除封面圖
    case 'delete_cover':
        $Tadnews->delete_cover($nsn);
        header("location:post.php?op=tad_news_form&nsn=$nsn");
        exit;

    default:
        $Tadnews->tad_news_form($nsn, $ncsn);
        $op = 'tad_news_form';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('now_op', $op);
$xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoTheme->addStylesheet('modules/tadnews/css/module.css');
if ($xoopsModuleConfig['use_table_shadow']) {
    $xoTheme->addStylesheet('modules/tadnews/css/module2.css');
}
$xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
require_once XOOPS_ROOT_PATH . '/footer.php';
