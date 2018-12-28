<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/block_function.php";

//區塊主函式 (顯示頁籤新聞)
function tadnews_tag_news($options)
{
    //$block=list_block_cate_news($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
    global $xoTheme;

    $tags = block_news_tags();
    // die(var_export($tags));
    if (empty($options[0])) {
        $tag_sn_arr = array_keys($tags['tags']);
        // die(var_export($tag_sn_arr));
    } else {
        $tag_sn_arr = explode(',', $options[0]);
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadnews/class/tadnews.php";
    $tadnews = new tadnews();
    $tadnews->set_news_kind("news");
    foreach ($tag_sn_arr as $tag_sn) {
        $tadnews->set_view_tag($tag_sn);
        $tadnews->set_show_num($options[1]);
        $block['all_news'][$tag_sn] = $tadnews->get_news('return');
        $block['tags'][$tag_sn]     = $tags['tags'][$tag_sn];
    }

    // die(var_export($block['all_news'][1]));

    if (empty($block['all_news'])) {
        return;
    }

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/easy_responsive_tabs.php";
    $randStr         = randStr();
    $responsive_tabs = new easy_responsive_tabs('#tag_news_' . $randStr, $options[2], $options[3], $options[4], $options[5], $options[6]);
    $responsive_tabs->rander();
    $block['tag_news_name'] = 'tag_news_' . $randStr;
    $block['min_height']    = sizeof($ncsn_arr) * 55;

    if ($options[7] == '1') {
        $tadnews = new tadnews();
        $tadnews->set_show_num($options[1]);
        $tadnews->set_show_mode('list');
        $tadnews->set_news_kind("news");
        $tadnews->set_use_star_rating(false);
        $tadnews->set_cover(false);
        $tadnews->set_view_tag($tag_sn_arr);
        $news                 = $tadnews->get_news('return');
        $block['latest_news'] = $news['page'];
    }

    return $block;
}

//區塊編輯函式
function tadnews_tag_news_edit($options)
{
    $option = block_news_tags($options[0]);

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
                <input type='radio' name='options[2]' value='default' " . chk($options[2], 'default', 1) . ">" . _MB_TADNEWS_TAB_NEWS_DEFAULT . "
                <input type='radio' name='options[2]' value='vertical' " . chk($options[2], 'vertical', 0) . ">" . _MB_TADNEWS_TAB_NEWS_VERTICAL . "
                <input type='radio' name='options[2]' value='accordion' " . chk($options[2], 'accordion', 0) . ">" . _MB_TADNEWS_TAB_NEWS_ACCORDION . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_BG . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[3]' value='{$options[3]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_INACTIV_BG . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[4]' value='{$options[4]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_BORDER_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[5]' value='{$options[5]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ACTIVE_CONTENT_BORDER_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[6]' value='{$options[6]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_ADD_ALL_NEWS_TAB . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[7]'  value='1' " . chk($options[7], '1') . ">" . _YES . "
                <input type='radio' name='options[7]'  value='0' " . chk($options[7], '0', 1) . ">" . _NO . "
            </div>
        </li>
    </ol>";

    return $form;
}

//單選回復原始資料函數
if (!function_exists("chk")) {
    function chk($DBV = "", $NEED_V = "", $defaul = "", $return = "checked")
    {
        if ($DBV == $NEED_V) {
            return $return;
        } elseif (empty($DBV) && $defaul == '1') {
            return $return;
        }
        return "";
    }
}
