<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (本站最新消息)
function tadnews_content_block_show($options)
{
    global $xoTheme;

    $ncsn_arr = explode(',', $options[7]);

    $Tadnews = new Tadnews();
    $Tadnews->set_show_num($options[0]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('summary');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary($options[1], $options[2]);
    $Tadnews->set_title_length($options[3]);
    $Tadnews->set_cover($options[4], $options[5]);
    $Tadnews->set_skip_news($options[6]);
    $Tadnews->set_use_star_rating(false);
    $block = $Tadnews->get_news('return');
    if (empty($block['page'])) {
        return;
    }

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    return $block;
}

//區塊編輯函式
function tadnews_content_block_edit($options)
{
    $options4_1 = ('1' == $options[4]) ? 'checked' : '';
    $options4_0 = ('0' == $options[4]) ? 'checked' : '';

    $block_news_cate = block_news_cate($options[7]);

    $form = "{$block_news_cate['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>
                <span class='my-help'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <textarea name='options[2]' class='my-input'>{$options[2]}</textarea>
                <span class='my-example'>color:gray;font-size: 0.8em;margin-top:3px;line-height:150%;</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
                <span class='my-help'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM4 . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[4]' value='1' $options4_1>" . _YES . "
                <input type='radio' name='options[4]' value='0' $options4_0>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM5 . "</lable>
            <div class='my-content'>
                <textarea name='options[5]' class='my-input'>{$options[5]}</textarea>
                <span class='my-example'>width:80px;height:60px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_START_FROM . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[6]' value='{$options[6]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$block_news_cate['form']}
                <input type='hidden' name='options[7]' id='bb' value='{$options[7]}'>
            </div>
        </li>
    </ol>
    ";

    return $form;
}
