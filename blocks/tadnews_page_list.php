<?php

//區塊主函式 (自訂頁面選單)
function tadnews_page_list($options)
{
    global $xoopsDB;

    if (empty($options[0])) {
        $sql        = "SELECT ncsn FROM " . $xoopsDB->prefix("tad_news_cate") . " WHERE not_news='1' AND of_ncsn=0 ORDER BY ncsn LIMIT 0,1";
        $result     = $xoopsDB->query($sql);
        list($ncsn) = $xoopsDB->fetchRow($result);
    } else {
        $ncsn = (int) $options[0];
    }

    $sql    = "select ncsn,of_ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where not_news='1' and `ncsn`='{$ncsn}'";
    $result = $xoopsDB->query($sql);

    list($block['ncsn'], $block['of_ncsn'], $block['nc_title']) = $xoopsDB->fetchRow($result);

    $myts = MyTextSanitizer::getInstance();
    //第一層底下的文章
    $sql    = "select nsn,news_title from " . $xoopsDB->prefix("tad_news") . " where ncsn='$ncsn' order by page_sort";
    $result = $xoopsDB->query($sql);
    while (list($nsn, $news_title) = $xoopsDB->fetchRow($result)) {
        $page['page' . $nsn]['type']    = 'page';
        $page['page' . $nsn]['padding'] = 1;
        $page['page' . $nsn]['title']   = $myts->htmlSpecialChars($news_title);
        $page['page' . $nsn]['url']     = XOOPS_URL . "/modules/tadnews/page.php?nsn={$nsn}";
    }
    if ($options[2] == 1) {
        //第一層底下的目錄
        $sql    = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where of_ncsn='$ncsn' order by sort";
        $result = $xoopsDB->query($sql);
        while (list($ncsn1, $nc_title) = $xoopsDB->fetchRow($result)) {
            $page['cate' . $ncsn1]['type']    = 'cate';
            $page['cate' . $ncsn1]['padding'] = 1;
            $page['cate' . $ncsn1]['title']   = $myts->htmlSpecialChars($nc_title);
            $page['cate' . $ncsn1]['url']     = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn1}";

            //第二層底下的文章
            $sql2    = "select nsn,news_title from " . $xoopsDB->prefix("tad_news") . " where ncsn='$ncsn1' order by page_sort";
            $result2 = $xoopsDB->query($sql2);
            while (list($nsn, $news_title) = $xoopsDB->fetchRow($result2)) {
                $page['page' . $nsn]['type']    = 'page';
                $page['page' . $nsn]['padding'] = 2;
                $page['page' . $nsn]['title']   = $myts->htmlSpecialChars($news_title);
                $page['page' . $nsn]['url']     = XOOPS_URL . "/modules/tadnews/page.php?nsn={$nsn}";
            }

            //第三層底下的目錄
            $sql2    = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where of_ncsn='$ncsn1' order by sort";
            $result2 = $xoopsDB->query($sql2);
            while (list($ncsn2, $nc_title) = $xoopsDB->fetchRow($result2)) {
                $page['cate' . $ncsn2]['type']    = 'cate';
                $page['cate' . $ncsn2]['padding'] = 2;
                $page['cate' . $ncsn2]['title']   = $myts->htmlSpecialChars($nc_title);
                $page['cate' . $ncsn2]['url']     = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn2}";

                //第三層底下的文章
                $sql3    = "select nsn,news_title from " . $xoopsDB->prefix("tad_news") . " where ncsn='$ncsn2' order by page_sort";
                $result3 = $xoopsDB->query($sql3);
                while (list($nsn, $news_title) = $xoopsDB->fetchRow($result3)) {
                    $page['page' . $nsn]['type']    = 'page';
                    $page['page' . $nsn]['padding'] = 3;
                    $page['page' . $nsn]['title']   = $myts->htmlSpecialChars($news_title);
                    $page['page' . $nsn]['url']     = XOOPS_URL . "/modules/tadnews/page.php?nsn={$nsn}";
                }
            }
        }
    }
    $block['pages']      = $page;
    $block['panel']      = $options[1];
    $block['show_title'] = $options[3];

    return $block;
}

//區塊編輯函式
function tadnews_page_list_edit($options)
{
    $cate          = block_get_all_not_news_cate(0, $options[0]);
    $panel_no      = $options[1] == '' ? 'selected' : '';
    $panel_default = $options[1] == 'default' ? 'selected' : '';
    $panel_primary = $options[1] == 'primary' ? 'selected' : '';
    $panel_success = $options[1] == 'success' ? 'selected' : '';
    $panel_info    = $options[1] == 'info' ? 'selected' : '';
    $panel_warning = $options[1] == 'warning' ? 'selected' : '';
    $panel_danger  = $options[1] == 'danger' ? 'selected' : '';

    $sub_cate        = $options[2] == 1 ? 'checked' : '';
    $no_sub_cate     = $options[2] == 0 ? 'checked' : '';
    $show_title      = $options[3] != 0 ? 'checked' : '';
    $dont_show_title = $options[3] == 0 ? 'checked' : '';

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM0 . "</lable>
            <div class='my-content'>
                <select name='options[0]' class='my-input'>
                $cate
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_PANEL_COLOR . "</lable>
            <div class='my-content'>
                <select name='options[1]' class='my-input'>
                    <option value='' $panel_no>" . _MB_TADNEWS_PAGE_PANEL_NO . "</option>
                    <option value='default' $panel_default>" . _MB_TADNEWS_PAGE_PANEL_DEFAULT . "</option>
                    <option value='primary' $panel_primary>" . _MB_TADNEWS_PAGE_PANEL_PRIMARY . "</option>
                    <option value='success' $panel_success>" . _MB_TADNEWS_PAGE_PANEL_SUCCESS . "</option>
                    <option value='info' $panel_info>" . _MB_TADNEWS_PAGE_PANEL_INFO . "</option>
                    <option value='warning' $panel_warning>" . _MB_TADNEWS_PAGE_PANEL_WARNING . "</option>
                    <option value='danger' $panel_danger>" . _MB_TADNEWS_PAGE_PANEL_DANGER . "</option>
                </select>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SUB_CATE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[2]' value='1' id='sub_cate' $sub_cate>" . _YES . "
                <input type='radio' name='options[2]' value='0' id='no_sub_cate' $no_sub_cate>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SHOW_TITLE . "</lable>
            <div class='my-content'>
            <INPUT type='radio' name='options[3]' value='1' id='show_title' $show_title>" . _YES . "
            <INPUT type='radio' name='options[3]' value='0' id='dont_show_title' $dont_show_title>" . _NO . "
            </div>
        </li>
    </ol>";
    return $form;
}

//取得所有類別標題
if (!function_exists("block_get_all_not_news_cate")) {
    function block_get_all_not_news_cate($of_ncsn = 0, $default_ncsn = 0, $level = 0)
    {
        global $xoopsDB, $xoopsUser, $xoopsModule;

        $left = $level * 10;
        $level += 1;

        $option = ($of_ncsn) ? "" : "<option value='0'></option>";
        $sql    = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where not_news='1' and of_ncsn='{$of_ncsn}' order by sort";
        $result = $xoopsDB->query($sql);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $selected = ($default_ncsn == $ncsn) ? "selected" : "";
            $option .= "<option value='{$ncsn}' style='padding-left: {$left}px' $selected>{$nc_title}</option>";
            $option .= block_get_all_not_news_cate($ncsn, $default_ncsn, $level);
        }
        return $option;
    }
}
