<?php

use Xmf\Request;
use XoopsModules\Tadnews\Tools;

if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;

if (!class_exists('XoopsModules\Tadtools\Ztree')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (自訂頁面樹狀目錄)
function tadnews_page($options)
{
    global $xoopsDB;
    if (empty($options[0])) {
        $sql        = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `of_ncsn`=0 ORDER BY `ncsn` LIMIT 0,1';
        $result     = Utility::query($sql, 's', ['1']);
        list($ncsn) = $xoopsDB->fetchRow($result);
    } else {
        $ncsn = (int) $options[0];
    }

    $pages = Tools::get_pages_recursive($ncsn, 0, true);

    $mydata[] = "{ id:'cate{$ncsn}', pId:0, name:'All', url:'" . XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}', target:'_self', open:true}";
    foreach ($pages as $id => $page) {
        if ($page['type'] == 'cate') {
            $pId        = "cate{$page['of_ncsn']}";
            $ncsn       = Request::getInt('ncsn');
            $font_style = (int) $ncsn == (int) $page['ncsn'] ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        } else {
            $pId        = "cate{$page['ncsn']}";
            $nsn        = Request::getInt('nsn');
            $font_style = (int) $nsn == (int) $page['nsn'] ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        }
        $mydata[] = "{ id:'{$id}', pId:'{$pId}', name:'{$page['title']}', url:'{$page['url']}', target:'_self', open:true {$font_style}}";
    }

    $page_json          = implode(',', (Array) $mydata);
    $randStr            = Utility::randStr(8);
    $ztree              = new Ztree("tadnews_mypage_tree_{$randStr}", $page_json, '', '', "of_ncsn", "ncsn");
    $block['ztree']     = $ztree->render();
    $block['width']     = $options[1];
    $block['font_size'] = $options[2];
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
        global $xoopsDB;

        $left = $level * 10;
        $level += 1;

        $option = ($of_ncsn) ? '' : "<option value='0'></option>";
        $sql    = 'SELECT `ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `of_ncsn`=? ORDER BY `sort`';
        $result = Utility::query($sql, 'si', [1, $of_ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $selected = ($default_ncsn == $ncsn) ? 'selected' : '';
            $option .= "<option value='{$ncsn}' style='padding-left: {$left}px' $selected>{$nc_title}</option>";
            $option .= block_get_all_not_news_cate($ncsn, $default_ncsn, $level);
        }

        return $option;
    }
}
