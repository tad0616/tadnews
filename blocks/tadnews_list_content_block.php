<?php
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (條列式新聞)
function tadnews_list_content_block_show($options)
{
    global $xoTheme;

    $block['jquery_path'] = Utility::get_jquery();
    $block['randStr'] = Utility::randStr(8);
    $block['num'] = $options[0];
    $block['summary_length'] = $options[1];
    $block['summary_css'] = $options[2];
    $block['title_length'] = $options[3];
    $block['show_cover'] = $options[4];
    $block['cover_css'] = $options[5];
    $block['start_from'] = $options[6];
    $block['searchbar'] = $options[10];
    if (!is_numeric($options[7]) and false === mb_strpos($options[7], ',')) {
        $options[7] = '';
    }

    $block['show_ncsn'] = isset($options[7]) ? $options[7] : '';
    $block['display_mode'] = empty($options[8]) ? 'list' : $options[8];
    $block['show_button'] = $options[9];
    $block['HTTP_HOST'] = XOOPS_URL;
    $block['ncsn'] = get_all_news_cate($options[7]);
    $block['tag'] = get_all_news_tag();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addScript('modules/tadtools/My97DatePicker/WdatePicker.js');

    return $block;
}

//區塊編輯函式
function tadnews_list_content_block_edit($options)
{
    $options4_1 = ('1' == $options[4]) ? 'checked' : '';
    $options4_0 = ('0' == $options[4]) ? 'checked' : '';
    $options8_1 = ('list' === $options[8]) ? 'checked' : '';
    $options8_0 = ('table' === $options[8]) ? 'checked' : '';
    $chked1_0 = ('1' == $options[9]) ? 'checked' : '';
    $chked1_1 = ('0' == $options[9]) ? 'checked' : '';
    $searchbar_0 = ('0' == $options[10]) ? 'checked' : '';
    $searchbar_1 = ('1' == $options[10]) ? 'checked' : '';

    $option = block_news_cate($options[7]);

    $form = "{$option['js']}
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
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3 . '' . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
                <span class='my-help'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM4 . '' . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[4]' value='1' $options4_1>" . _YES . "
                <input type='radio' name='options[4]' value='0' $options4_0>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2 . '' . _MB_TADNEWS_LIST_TEMPLATE_NOTE . "</lable>
            <div class='my-content'>
                <textarea name='options[5]' class='my-input'>{$options[5]}</textarea>
                <span class='my-example'>width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;</span>
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
                {$option['form']}
                <input type='hidden' name='options[7]' id='bb' value='{$options[7]}'>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_TEMPLATE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[8]' value='list' $options8_1>" . _MB_TADNEWS_LIST_TEMPLATE_LIST . "
                <input type='radio' name='options[8]' value='table' $options8_0>" . _MB_TADNEWS_LIST_TEMPLATE_TABLE . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='radio' $chked1_0 name='options[9]' value='1'>" . _YES . "
                <input type='radio' $chked1_1 name='options[9]' value='0'>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SEARCHBAR . "</lable>
            <div class='my-content'>
                <input type='radio' $searchbar_1 name='options[10]' value='1'>" . _YES . "
                <input type='radio' $searchbar_0 name='options[10]' value='0'>" . _NO . '
            </div>
        </li>
    </ol>
    ';

    return $form;
}
