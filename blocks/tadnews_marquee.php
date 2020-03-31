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

//區塊主函式 (跑馬燈區塊)
function tadnews_marquee($options)
{
    global $xoTheme;
    $ncsn_arr = [];
    if (isset($options[1])) {
        $ncsn_arr = explode(',', $options[1]);
    }

    $Tadnews = new Tadnews();

    $Tadnews->set_show_num($options[0]);
    $Tadnews->set_view_ncsn($ncsn_arr);
    $Tadnews->set_show_mode('list');
    $Tadnews->set_news_kind('news');
    $Tadnews->set_use_star_rating(false);
    $block = $Tadnews->get_news('return');
    if (empty($block['page'])) {
        return;
    }

    $block['direction'] = empty($options[2]) ? 'down' : $options[2];
    $block['duration'] = empty($options[3]) ? '5000' : $options[3];
    $block['css'] = empty($options[4]) ? '' : $options[4];
    $block['item_css'] = empty($options[5]) ? '' : $options[5];
    $block['randStr'] = Utility::randStr();
    $block['jquery'] = Utility::get_jquery();
    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');
    $xoTheme->addScript('modules/tadnews/class/jQuery.Marquee/jquery.marquee.min.js');

    return $block;
}

//區塊編輯函式
function tadnews_marquee_edit($options)
{
    $option = block_news_cate($options[1]);

    $left = 'left' === $options[2] ? 'checked' : '';
    $right = 'right' === $options[2] ? 'checked' : '';
    $up = 'up' === $options[2] ? 'checked' : '';
    $down = 'down' === $options[2] ? 'checked' : '';

    $form = "{$option['js']}
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[0]' value='{$options[0]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_CATE_NEWS_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                {$option['form']}
                <input type='hidden' name='options[1]' id='bb' value='{$options[1]}'>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_DIRECTION . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='left' $left>" . _MB_TADNEWS_MARQUEE_DIRECTION_LEFT . "
                <input type='radio' name='options[2]' value='right' $right>" . _MB_TADNEWS_MARQUEE_DIRECTION_RIGHT . "
                <input type='radio' name='options[2]' value='up' $up>" . _MB_TADNEWS_MARQUEE_DIRECTION_UP . "
                <input type='radio' name='options[2]' value='down' $down>" . _MB_TADNEWS_MARQUEE_DIRECTION_DOWN . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_DURATION . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
                <span class='my-help'>" . _MB_TADNEWS_MARQUEE_DIRECTION_DESC . "</span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_CSS . "</lable>
            <div class='my-content'>
            <textarea name='options[4]' class='my-input'>{$options[4]}</textarea>
                <span class='my-example'><br>
                width: 100%; /* 跑馬燈寬度 */<br>
                height:4em; /* 跑馬燈高度 */<br>
                line-height:1.8; /* 跑馬燈行高 */<br>
                border:1px solid #cfcfcf; /* 跑馬燈邊框 */<br>
                background-color:#FCFCFC; /* 跑馬燈底色 */<br>
                box-shadow: 0px 1px 2px 1px #cfcfcf inset; /* 跑馬燈陰影 */
                </span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_MARQUEE_ITEM_CSS . "</lable>
            <div class='my-content'>
            <textarea name='options[5]' class='my-input'>{$options[5]}</textarea>
                <span class='my-example'><br>
                line-height:1.4;<br>
                margin:5px;
                </span>
            </div>
        </li>
    </ol>";

    return $form;
}
