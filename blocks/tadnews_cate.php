<?php
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;
if (!class_exists('XoopsModules\Tadtools\Ztree')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (顯示所有新聞的類別)
function tadnews_cate_show($options)
{
    global $xoopsDB;

    $cates  = $counter  = [];
    $sql    = 'SELECT COUNT(*), `ncsn` FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `enable`=? GROUP BY `ncsn`';
    $result = Utility::query($sql, 's', [1]) or Utility::web_error($sql, __FILE__, __LINE__);

    while (list($count, $ncsn) = $xoopsDB->fetchRow($result)) {
        $counter[$ncsn] = $count;
    }

    $sql    = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`!=? ORDER BY `sort`';
    $result = Utility::query($sql, 's', [1]);

    while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $cates['title'][$ncsn]   = $nc_title;
        $cates['of_ncsn'][$ncsn] = $of_ncsn;
        $cates['url'][$ncsn]     = XOOPS_URL . "/modules/tadnews/index.php?ncsn={$ncsn}";
    }

    if (empty($cates)) {
        $block = '';
    } else {

        $data[] = "{ id:0, pId:0, name:'" . _MB_TADNEWS_NO_CATE . "', url:'" . XOOPS_URL . "/modules/tadnews/index.php?ncsn=0', target:'_self', open:true}";

        foreach ($cates['title'] as $ncsn => $title) {
            $nc_title = addslashes($title);
            $c        = $counter[$ncsn] ? "({$counter[$ncsn]})" : '';
            $data[]   = "{ id:{$ncsn}, pId:{$cates['of_ncsn'][$ncsn]}, name:'{$nc_title} {$c}', url:'{$cates['url'][$ncsn]}', open: true ,target:'_self' }";
        }
        // Utility::dd($data);
        $json = implode(",\n", (Array) $data);

        $Ztree = new Ztree('tadnews_cate_tree', $json, '', '', 'of_ncsn', 'ncsn');
        $block = $Ztree->render();
    }

    return $block;
}
