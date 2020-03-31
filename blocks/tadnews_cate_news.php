<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (分類新聞區塊)
function tadnews_cate_news($options)
{
    //$block=list_block_cate_news($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
    global $xoTheme;

    $ncsn_arr = explode(',', $options[0]);

    $Tadnews = new Tadnews();
    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode('cate');
    $Tadnews->set_show_num($options[1]);
    $Tadnews->set_summary($options[4], $options[5]);
    $Tadnews->set_cover($options[2]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $block = $Tadnews->get_cate_news('return');
    if (empty($block['all_news'])) {
        return;
    }

    $block['show_line'] = ('1' == $options[3]) ? 'table' : '';
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    return $block;
}

//區塊編輯函式
function tadnews_cate_news_edit($options)
{
    $option = block_news_cate($options[0]);

    $form = "
    {$option['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[0]' id='bb' value='{$options[0]}'>
            </div>
        </li>

        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' name='options[1]' value='{$options[1]}' class='my-input' size=3>
            </div>
        </li>

        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='1' " . Utility::chk($options[2], 1, '1') . '>' . _YES . "
                <input type='radio' name='options[2]' value='0' " . Utility::chk($options[2], 0) . '>' . _NO . "
            </div>
        </li>

        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM3 . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[3]' value='1' " . Utility::chk($options[3], 1, '1') . '>' . _YES . "
                <input type='radio' name='options[3]' value='0' " . Utility::chk($options[3], 0) . '>' . _NO . "
            </div>
        </li>

        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' name='options[4]' value='{$options[4]}' class='my-input' size=5>
            </div>
        </li>

        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <textarea name='options[5]' class='my-input'>{$options[5]}</textarea>
            </div>
        </li>
    </ol>
    ";

    return $form;
}
