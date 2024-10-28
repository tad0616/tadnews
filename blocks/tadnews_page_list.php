<?php
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}

use XoopsModules\Tadtools\MColorPicker;
if (!class_exists('XoopsModules\Tadtools\MColorPicker')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (自訂頁面列表)
function tadnews_page_list($options)
{
    global $xoopsDB, $xoTheme;
    $xoTheme->addStylesheet('modules/tadtools/css/vertical_menu.css');

    if (empty($options[0])) {
        $sql = 'SELECT `ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `of_ncsn`=0 ORDER BY `ncsn` LIMIT 0,1';
        $result = Utility::query($sql, 's', [1]);
        $row = $xoopsDB->fetchRow($result);
        $ncsn = $row[0] ?? 0;
    } else {
        $ncsn = (int) $options[0];
    }

    $sql = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `ncsn` =?';
    $result = Utility::query($sql, 'si', [1, $ncsn]);

    $row = $xoopsDB->fetchRow($result);
    $block = [
        'ncsn' => $row[0] ?? '',
        'of_ncsn' => $row[1] ?? '',
        'nc_title' => $row[2] ?? '',
    ];

    // 遞迴獲取所有層級的分類和文章
    $block['pages'] = Tools::get_pages_recursive($ncsn, 0, $options[2]);

    $block['bgcolor'] = $options[1];
    $block['color'] = $options[4];
    $block['show_title'] = $options[3];
    $block['bg_css'] = $options[5];
    $block['text_css'] = $options[6];

    return $block;
}

//區塊編輯函式
function tadnews_page_list_edit($options)
{
    $cate = block_get_all_not_news_cate(0, $options[0]);

    $sub_cate = 1 == $options[2] ? 'checked' : '';
    $no_sub_cate = 0 == $options[2] ? 'checked' : '';
    $show_title = 0 != $options[3] ? 'checked' : '';
    $dont_show_title = 0 == $options[3] ? 'checked' : '';

    $MColorPicker = new MColorPicker('.color-picker');
    $MColorPicker->render('bootstrap');

    $form = "
    .color-picker {
        width: 80%;
        display: inline-block;
    }
    </style>
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
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_COLOR . "</lable>
            <div class='my-content'>
                <div class='input-group'>
                    <input type='text' class='my-input color-picker' data-hex='true' name='options[1]' value='{$options[1]}' size=6>
                </div>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SUB_CATE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='1' id='sub_cate' $sub_cate>" . _YES . "
                <input type='radio' name='options[2]' value='0' id='no_sub_cate' $no_sub_cate>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SHOW_TITLE . "</lable>
            <div class='my-content'>
            <INPUT type='radio' name='options[3]' value='1' id='show_title' $show_title>" . _YES . "
            <INPUT type='radio' name='options[3]' value='0' id='dont_show_title' $dont_show_title>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_COLOR . "</lable>
            <div class='my-content'>
                <div class='input-group'>
                    <input type='text' class='my-input color-picker' data-hex='true' name='options[4]' value='{$options[4]}' size=6>
                </div>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[5]'>{$options[5]}</textarea>
                <span class='my-example'><br>
                padding: 4px; border-radius: 5px;
                </span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[6]'>{$options[6]}</textarea>
                <span class='my-example'><br>
                font-size: 1.1rem; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;
                </span>
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
        $sql = 'SELECT `ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `of_ncsn`=? ORDER BY `sort`';
        $result = Utility::query($sql, 'si', [1, $of_ncsn]);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $selected = ($default_ncsn == $ncsn) ? 'selected' : '';
            $option .= "<option value='{$ncsn}' style='padding-left: {$left}px' $selected>{$nc_title}</option>";
            $option .= block_get_all_not_news_cate($ncsn, $default_ncsn, $level);
        }

        return $option;
    }
}
