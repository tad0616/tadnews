<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/block_function.php";

//區塊主函式 (顯示頁籤新聞)
function tadnews_tab_news($options)
{
    //$block=list_block_cate_news($options[0],$options[1],$options[2],$options[3],$options[4],$options[5]);
    global $xoTheme;

    $ncsn_arr = explode(',', $options[0]);

    include_once XOOPS_ROOT_PATH . "/modules/tadnews/class/tadnews.php";
    $tadnews = new tadnews();
    $tadnews->set_news_kind("news");
    $tadnews->set_show_mode('cate');
    $tadnews->set_show_num($options[1]);
    $tadnews->set_view_ncsn($ncsn_arr);
    $block = $tadnews->get_cate_news('return');
    if (empty($block['all_news'])) {
        return;
    }

    $xoTheme->addStylesheet('modules/tadtools/css/iconize.css');

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/easy_responsive_tabs.php";
    $randStr         = randStr();
    $responsive_tabs = new easy_responsive_tabs('#tab_news_' . $randStr, $options[2], $options[3], $options[4], $options[5], $options[6]);
    $responsive_tabs->rander();
    $block['tab_news_name'] = 'tab_news_' . $randStr;
    $block['min_height']    = sizeof($ncsn_arr) * 55;

    if ($options[7] == '1') {
        $tadnews = new tadnews();
        $tadnews->set_show_mode('list');
        $tadnews->set_show_num($options[1]);
        $tadnews->set_news_kind("news");
        $tadnews->set_use_star_rating(false);
        $tadnews->set_cover(false);
        $tadnews->set_view_ncsn($ncsn_arr);
        $news                 = $tadnews->get_news('return');
        $block['latest_news'] = $news['page'];
    }
    return $block;
}

//區塊編輯函式
function tadnews_tab_news_edit($options)
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
