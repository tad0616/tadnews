<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\LofSliderNews;
if (!class_exists('XoopsModules\Tadtools\LofSliderNews')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (滑動新聞)
function tadnews_slidernews_show($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsOption;

    $ncsn_arr = explode(',', $options[4]);

    $Tadnews = new Tadnews();

    $Tadnews->set_show_num($options[2]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('summary');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_summary($options[3]);
    $Tadnews->set_use_star_rating(false);
    $Tadnews->set_cover(false);
    $all_news = $Tadnews->get_news('return');

    if (empty($all_news['page'])) {
        return;
    }

    $n = 0;
    $LofSliderNews = new LofSliderNews($options[0], $options[1], $options[3]);
    $pic_num = 1;
    foreach ($all_news['page'] as $news) {
        $big_image = empty($news['image_big']) ? XOOPS_URL . "/modules/tadnews/images/demo{$pic_num}.jpg" : $news['image_big'];
        $LofSliderNews->add_content($news['nsn'], $news['news_title'], $news['content'], $big_image, $news['post_date'], XOOPS_URL . "/modules/tadnews/index.php?nsn={$news['nsn']}");
        $n++;
        $pic_num++;
        if ($n >= $options[2]) {
            break;
        }
    }

    $block = $LofSliderNews->render();

    return $block;
}

//區塊編輯函式
function tadnews_slidernews_edit($options)
{
    $block_news_cate = block_news_cate($options[4]);

    $form = "{$block_news_cate['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[2]' value='{$options[2]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM3 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$block_news_cate['form']}
                <INPUT type='hidden' name='options[4]' id='bb' value='{$options[4]}'>
            </div>
        </li>
    </ol>";

    return $form;
}
