<?php
use Xmf\Request;
use XoopsModules\Tadnews\Paper;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadnews_newspaper.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$ncsn = Request::getInt('ncsn');
$npsn = Request::getInt('npsn');

switch ($op) {
    case 'preview':
        $main = Paper::preview_newspaper($npsn);
        break;
    default:
        $main = Paper::list_newspaper();
        break;
}

/*-----------秀出結果區--------------*/
if ('preview' === $op) {
    echo $main;
} else {
    $xoopsTpl->assign('xoops_showrblock', 0);
    $xoopsTpl->assign('toolbar', Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
    require_once XOOPS_ROOT_PATH . '/footer.php';
}
