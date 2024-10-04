<?php

use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Dtree;
if (!class_exists('XoopsModules\Tadtools\Dtree')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (自訂頁面樹狀目錄)
function tadnews_page($options)
{
    global $xoopsDB;
    if (empty($options[0])) {
        $sql = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=1 AND `of_ncsn`=0 ORDER BY `ncsn` LIMIT 0,1';
        $result = Utility::query($sql);
        list($ncsn) = $xoopsDB->fetchRow($result);

    } else {
        $ncsn = (int) $options[0];
    }

    $sql = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=1 AND `ncsn`=?';
    $result = Utility::query($sql, 'i', [$ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result);

    $home['sn'] = $ncsn;
    $home['title'] = $nc_title;
    $home['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}";

    $page = Tools::block_get_page_cate($ncsn);

    $Dtree = new Dtree("tadnews_mypage_tree{$ncsn}", $home, $page['title'], $page['of_ncsn'], $page['url']);
    $block = $Dtree->render($options[2]);

    return $block;
}

//區塊編輯函式
function tadnews_page_edit($options)
{
    $cate = block_get_all_not_news_cate(0, $options[0]);

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                $cate
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[2]' value='{$options[2]}' size=6>
            </div>
        </li>
    </ol>";

    return $form;
}

//取得所有類別標題
if (!function_exists('block_get_all_not_news_cate')) {
    function block_get_all_not_news_cate($of_ncsn = 0, $default_ncsn = 0, $level = 0)
    {
        global $xoopsDB, $xoopsUser, $xoopsModule;

        $left = $level * 10;
        $level += 1;

        $option = ($of_ncsn) ? '' : "<option value='0'></option>";
        $sql = 'SELECT `ncsn`,`nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=1 AND `of_ncsn`=? ORDER BY `sort`';
        $result = Utility::query($sql, 'i', [$of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $selected = ($default_ncsn == $ncsn) ? 'selected' : '';
            $option .= "<option value='{$ncsn}' style='padding-left: {$left}px' $selected>{$nc_title}</option>";
            $option .= block_get_all_not_news_cate($ncsn, $default_ncsn, $level);
        }

        return $option;
    }
}
