<?php
use XoopsModules\Tadnews\Tadnews;
if (!class_exists('XoopsModules\Tadnews\Tadnews')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\MColorPicker;
use XoopsModules\Tadtools\Utility;
if (!class_exists('XoopsModules\Tadtools\Utility')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}
require_once XOOPS_ROOT_PATH . '/modules/tadnews/block_function.php';

//區塊主函式 (頁籤新聞區塊)
function tadnews_tab_news($options)
{
    //$block=list_block_cate_news($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
    global $xoTheme;

    $ncsn_arr = explode(',', $options[0]);

    $Tadnews = new Tadnews();
    $Tadnews->set_news_kind('news');
    $Tadnews->set_show_mode('cate');
    $Tadnews->set_show_num($options[1]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $block = $Tadnews->get_cate_news('return');
    if (empty($block['all_news'])) {
        return;
    }

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    $randStr = Utility::randStr();
    $EasyResponsiveTabs = new EasyResponsiveTabs('#tab_news_' . $randStr, $options[2], $options[3], $options[4], $options[5], $options[6]);
    $EasyResponsiveTabs->rander();
    $block['tab_news_name'] = 'tab_news_' . $randStr;
    $block['min_height'] = 200;
    $tab_font_size = empty($options[8]) ? 16 : (int) $options[8];
    $block['tab_font_size'] = round($tab_font_size / 16, 1);

    if ('1' == $options[7]) {
        $Tadnews = new Tadnews();
        $Tadnews->set_show_num($options[1]);
        $Tadnews->set_show_mode('list');
        $Tadnews->set_news_kind('news');
        $Tadnews->set_use_star_rating(false);
        $Tadnews->set_cover(false);
        $Tadnews->set_view_ncsn($ncsn_arr);
        $news = $Tadnews->get_news('return');
        $block['latest_news'] = $news['page'];
    }

    return $block;
}

//區塊編輯函式
function tadnews_tab_news_edit($options)
{
    $option = block_news_cate($options[0]);

    $MColorPicker = new MColorPicker('.color');
    $MColorPicker->render();

    $options[8] = empty($options[8]) ? 16 : (int) $options[8];

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
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_TAB_NEWS_DISPLAY_TYPE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='default' " . Utility::chk($options[2], 'default', 1) . '>' . _MB_TADNEWS_TAB_NEWS_DEFAULT . "
                <input type='radio' name='options[2]' value='vertical' " . Utility::chk($options[2], 'vertical', 0) . '>' . _MB_TADNEWS_TAB_NEWS_VERTICAL . "
                <input type='radio' name='options[2]' value='accordion' " . Utility::chk($options[2], 'accordion', 0) . '>' . _MB_TADNEWS_TAB_NEWS_ACCORDION . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_BG . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[3]' value='{$options[3]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_INACTIV_BG . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[4]' value='{$options[4]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_BORDER_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[5]' value='{$options[5]}' size=8>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_CONTENT_BORDER_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[6]' value='{$options[6]}' size=8>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ADD_ALL_NEWS_TAB . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[7]'  value='1' " . Utility::chk($options[7], '1') . ">" . _YES . "
                <input type='radio' name='options[7]'  value='0' " . Utility::chk($options[7], '0', 1) . ">" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_TAB_FONT_SIZE . "</lable>
            <div class='my-content'>
            <input type='text' class='my-input' name='options[8]' value='{$options[8]}' size=8> px
            </div>
        </li>
    </ol>";

    return $form;
}
