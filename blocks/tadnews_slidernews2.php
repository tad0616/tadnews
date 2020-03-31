<?php

use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\FlexSlider;
use XoopsModules\Tadtools\ResponsiveSlides;
if (!class_exists('XoopsModules\Tadtools\ResponsiveSlides')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (自動縮放的滑動新聞)
function tadnews_slidernews2_show($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption;

    if (empty($options[2])) {
        $options[2] = 'ResponsiveSlides';
    }

    $ncsn_arr = explode(',', $options[3]);

    $Tadnews = new Tadnews();

    $Tadnews->set_show_num($options[0]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('summary');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary($options[1]);
    $Tadnews->set_use_star_rating(false);
    $Tadnews->set_cover(false);
    $all_news = $Tadnews->get_news('return');

    if (empty($all_news['page'])) {
        return;
    }
    if ('flexslider2' === $options[2]) {
        $slider = new FlexSlider($options[1]);
    } else {
        $slider = new ResponsiveSlides($options[1]);
    }

    $n = 0;
    $pic_num = 1;
    foreach ($all_news['page'] as $news) {
        $big_image = empty($news['image_big']) ? XOOPS_URL . "/modules/tadnews/images/demo{$pic_num}.jpg" : $news['image_big'];
        $slider->add_content($news['nsn'], $news['news_title'], $news['content'], $big_image, $news['post_date'], XOOPS_URL . "/modules/tadnews/index.php?nsn={$news['nsn']}");
        $n++;
        $pic_num++;
        if ($n >= $options[0]) {
            break;
        }
    }

    $block = $slider->render();

    return $block;
}

function tadnews_slidernews2_edit($options)
{
    $ResponsiveSlides = 'ResponsiveSlides' === $options[2] ? 'selected' : '';
    $flexslider2 = 'flexslider2' === $options[2] ? 'selected' : '';

    $block_news_cate = block_news_cate($options[3]);

    $form = "{$block_news_cate['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM3 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM4 . "</lable>
            <div class='my-content'>
                <select name='options[2]' class='my-input'>
                    <option value='ResponsiveSlides' $ResponsiveSlides>ResponsiveSlides</option>
                    <option value='flexslider2' $flexslider2>flexslider2</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$block_news_cate['form']}
                <input type='hidden' name='options[3]' id='bb' value='{$options[3]}'>
            </div>
        </li>
    </ol>
    ";

    return $form;
}
