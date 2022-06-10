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
    global $xoTheme;
    $Tadnews = new Tadnews();

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
    $block['display_mode'] = $options[5];
    $block['width_left'] = $options[6] ? $options[6] : 1;
    $block['width_right'] = 12 - $block['width_left'];
    $block['height'] = $options[7] ? $options[7] : '80px';
    $block['demo_path'] = is_dir(XOOPS_ROOT_PATH . "/uploads/tadnews/demo") ? XOOPS_URL . "/uploads/tadnews/demo" : XOOPS_URL . "/modules/tadnews/images";

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addStylesheet('modules/tadnews/css/module.css');
    $modhandler = xoops_gethandler('module');
    $xoopsModule = $modhandler->getByDirname("tadnews");
    $config_handler = xoops_gethandler('config');
    $xoopsModuleConfig = $config_handler->getConfigsByCat(0, $xoopsModule->mid());
    if ($xoopsModuleConfig['use_table_shadow']) {
        $xoTheme->addStylesheet(XOOPS_URL . '/modules/tadnews/css/module2.css');
    }

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

    $options5_1 = ('1' == $options[5]) ? 'selected' : '';
    $options5_2 = ('2' == $options[5]) ? 'selected' : '';

    $options6_1 = ('1' == $options[6]) ? 'selected' : '';
    $options6_2 = ('2' == $options[6]) ? 'selected' : '';
    $options6_3 = ('3' == $options[6]) ? 'selected' : '';
    $options6_4 = ('4' == $options[6]) ? 'selected' : '';
    $options6_6 = ('6' == $options[6]) ? 'selected' : '';

    $option = block_news_cate($options[4]);

    $form = "{$option['js']}

    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT1 . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                    <option value='1' $options0_1>1</option>
                    <option value='2' $options0_2>2</option>
                    <option value='3' $options0_3>3</option>
                    <option value='4' $options0_4>4</option>
                    <option value='6' $options0_6>6</option>
                    <option value='12' $options0_12>12</option>
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
                <span class='my-example'>font-size: 0.8rem; color: #707070; line-height: 180%;</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[4]' id='bb' value='{$options[4]}'>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT5 . "</lable>
            <div class='my-content'>
                <select name='options[5]' class='my-input'>
                    <option value='1' $options5_1>" . _MB_TADNEWS_COVERED_OPT5_1 . "</option>
                    <option value='2' $options5_2>" . _MB_TADNEWS_COVERED_OPT5_2 . "</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT6 . "</lable>
            <div class='my-content'>
                <select name='options[6]' class='my-input'>
                    <option value='1' $options6_1>1</option>
                    <option value='2' $options6_2>2</option>
                    <option value='3' $options6_3>3</option>
                    <option value='4' $options6_4>4</option>
                    <option value='6' $options6_6>6</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_COVERED_OPT7 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[7]' value='{$options[7]}' size=6>
            </div>
        </li>
    </ol>";

    return $form;
}
