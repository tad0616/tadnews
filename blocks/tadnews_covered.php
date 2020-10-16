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

//區塊主函式 (圖文集區塊)
function tadnews_covered($options)
{
    global $xoopsDB, $xoTheme;
    $Tadnews = new Tadnews();

    $block['jquery_path'] = Utility::get_jquery();
    $block['randStr'] = Utility::randStr(8);

    $num = $options[0] * $options[1];
    if (empty($num)) {
        $num = 12;
    }

    $summary_length = (int) $options[2];
    $summary_css = $options[3];
    // $show_cover     = $block['show_cover'];
    $cover_css = '';
    $show_ncsn = isset($options[4]) ? $options[4] : '';
    $ncsn_arr = explode(',', $show_ncsn);

    $Tadnews->set_show_num($num);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary($summary_length);
    $Tadnews->set_cover(true, $cover_css);
    $Tadnews->set_use_star_rating(false);
    $news = $Tadnews->get_news('return');

    $block = $news;
    $block['num'] = 12 / $options[0];
    $block['cols'] = $options[0];
    $block['count'] = count($news['page']);
    $block['summary_css'] = $summary_css;

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    return $block;
}

//區塊編輯函式
function tadnews_covered_edit($options)
{
    $options0_1 = ('1' == $options[0]) ? 'selected' : '';
    $options0_2 = ('2' == $options[0]) ? 'selected' : '';
    $options0_3 = ('3' == $options[0]) ? 'selected' : '';
    $options0_4 = ('4' == $options[0]) ? 'selected' : '';
    $options0_6 = ('6' == $options[0]) ? 'selected' : '';
    $options0_12 = ('12' == $options[0]) ? 'selected' : '';

    $options4_1 = ('1' == $options[4]) ? 'checked' : '';
    $options4_0 = ('0' == $options[4]) ? 'checked' : '';
    $options8_1 = ('list' === $options[8]) ? 'checked' : '';
    $options8_0 = ('table' === $options[8]) ? 'checked' : '';
    $chked1_0 = ('1' == $options[9]) ? 'checked' : '';
    $chked1_1 = ('0' == $options[9]) ? 'checked' : '';

    $option = block_news_cate($options[4]);

    $form = "{$option['js']}

    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT1 . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                    <option vaue='1' $options0_1>1</option>
                    <option vaue='2' $options0_2>2</option>
                    <option vaue='3' $options0_3>3</option>
                    <option vaue='4' $options0_4>4</option>
                    <option vaue='6' $options0_6>6</option>
                    <option vaue='12' $options0_12>12</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT2 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[2]' value='{$options[2]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <textarea name='options[3]' class='my-input'>{$options[3]}</textarea>
                <span class='my-example'>font-size: font-size: 0.8em ;color: gray; line-height: 1.5;</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
            {$option['form']}
            <input type='hidden' name='options[4]' id='bb' value='{$options[4]}'>
            </div>
        </li>
    </ol>";

    return $form;
}
