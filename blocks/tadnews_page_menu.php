<?php
use Xmf\Request;
use XoopsModules\Tadnews\Tools;
if (!class_exists('XoopsModules\Tadnews\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}

use XoopsModules\Tadtools\MColorPicker;
if (!class_exists('XoopsModules\Tadtools\MColorPicker')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

function tadnews_page_menu($options)
{
    global $xoopsDB, $xoTheme;
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/vertical_menu.css');

    $ncsn = Request::getInt('ncsn');
    $nsn = Request::getInt('nsn');

    if (empty($ncsn) || strpos($_SERVER['REQUEST_URI'], 'page.php') === false) {
        return;
    }

    $sql = 'SELECT `ncsn`, `of_ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`=? AND `ncsn`=?';
    $result = Utility::query($sql, 'ii', [1, $ncsn]);
    $row = $xoopsDB->fetchRow($result);

    $block = [
        'ncsn' => $row[0] ?? '',
        'of_ncsn' => $row[1] ?? '',
        'nc_title' => $row[2] ?? '',
    ];

    // 遞迴獲取所有層級的分類和文章
    $block['pages'] = get_pages_recursive($ncsn, 0, $options[0]);

    $block['show_title'] = $options[1];
    $block['color'] = $options[2];
    $block['bgcolor'] = $options[3];
    $block['bg_css'] = $options[4];
    $block['text_css'] = $options[5];
    $block['now_nsn'] = $nsn;

    return $block;
}

//區塊編輯函式
function tadnews_page_menu_edit($options)
{
    $sub_cate = 1 == $options[0] ? 'checked' : '';
    $no_sub_cate = 0 == $options[0] ? 'checked' : '';
    $show_title = 0 != $options[1] ? 'checked' : '';
    $dont_show_title = 0 == $options[1] ? 'checked' : '';

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
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SUB_CATE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[0]' value='1' id='sub_cate' $sub_cate>" . _YES . "
                <input type='radio' name='options[0]' value='0' id='no_sub_cate' $no_sub_cate>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SHOW_TITLE . "</lable>
            <div class='my-content'>
            <INPUT type='radio' name='options[1]' value='1' id='show_title' $show_title>" . _YES . "
            <INPUT type='radio' name='options[1]' value='0' id='dont_show_title' $dont_show_title>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_COLOR . "</lable>
            <div class='my-content'>
                <div class='input-group'>
                    <input type='text' class='my-input color-picker' data-hex='true' name='options[2]' value='{$options[2]}' size=6>
                </div>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_COLOR . "</lable>
            <div class='my-content'>
                <div class='input-group'>
                    <input type='text' class='my-input color-picker' data-hex='true' name='options[3]' value='{$options[3]}' size=6>
                </div>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[4]'>{$options[4]}</textarea>
                <span class='my-example'><br>
                padding: 4px; border-radius: 5px;
                </span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[5]'>{$options[5]}</textarea>
                <span class='my-example'><br>
                font-size: 1.1rem; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;
                </span>
            </div>
        </li>
    </ol>";

    return $form;
}
