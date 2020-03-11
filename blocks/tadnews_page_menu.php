<?php
use XoopsModules\Tadtools\MColorPicker;
if (!class_exists('XoopsModules\Tadtools\MColorPicker')) {
    require XOOPS_ROOT_PATH . '/modules/tadtools/preloads/autoloader.php';
}

//區塊主函式 (自訂頁面選單)
function tadnews_page_menu($options)
{
    global $xoopsDB, $xoTheme;
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/vertical_menu.css');

    if (empty($_GET['ncsn']) or strpos($_SERVER['REQUEST_URI'], 'page.php') === false) {
        return;
    }

    $ncsn = (int) $_GET['ncsn'];

    $sql = 'select ncsn,of_ncsn,nc_title from ' . $xoopsDB->prefix('tad_news_cate') . " where not_news='1' and `ncsn`='{$ncsn}'";
    $result = $xoopsDB->query($sql);
    list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result);

    $block['ncsn'] = $ncsn;
    $block['of_ncsn'] = $of_ncsn;
    $block['nc_title'] = $nc_title;

    $myts = \MyTextSanitizer::getInstance();
    //第一層底下的文章
    $sql = 'select nsn,news_title from ' . $xoopsDB->prefix('tad_news') . " where ncsn='$ncsn' order by page_sort";
    $result = $xoopsDB->query($sql);
    while (list($nsn, $news_title) = $xoopsDB->fetchRow($result)) {
        $page['page' . $nsn]['type'] = 'page';
        $page['page' . $nsn]['padding'] = 0;
        $page['page' . $nsn]['title'] = $myts->htmlSpecialChars($news_title);
        $page['page' . $nsn]['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}&nsn={$nsn}";
        $page['page' . $nsn]['nsn'] = $nsn;
    }
    if (1 == $options[0]) {
        //第一層底下的目錄
        $sql = 'select ncsn,nc_title from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='$ncsn' order by sort";
        $result = $xoopsDB->query($sql);
        while (list($ncsn1, $nc_title) = $xoopsDB->fetchRow($result)) {
            $page['cate' . $ncsn1]['type'] = 'cate';
            $page['cate' . $ncsn1]['padding'] = 0;
            $page['cate' . $ncsn1]['title'] = $myts->htmlSpecialChars($nc_title);
            $page['cate' . $ncsn1]['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn1}";

            //第二層底下的文章
            $sql2 = 'select nsn,news_title from ' . $xoopsDB->prefix('tad_news') . " where ncsn='$ncsn1' order by page_sort";
            $result2 = $xoopsDB->query($sql2);
            while (list($nsn, $news_title) = $xoopsDB->fetchRow($result2)) {
                $page['page' . $nsn]['type'] = 'page';
                $page['page' . $nsn]['padding'] = 1;
                $page['page' . $nsn]['title'] = $myts->htmlSpecialChars($news_title);
                $page['page' . $nsn]['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn1}&nsn={$nsn}";
                $page['page' . $nsn]['nsn'] = $nsn;
            }

            //第三層底下的目錄
            $sql2 = 'select ncsn,nc_title from ' . $xoopsDB->prefix('tad_news_cate') . " where of_ncsn='$ncsn1' order by sort";
            $result2 = $xoopsDB->query($sql2);
            while (list($ncsn2, $nc_title) = $xoopsDB->fetchRow($result2)) {
                $page['cate' . $ncsn2]['type'] = 'cate';
                $page['cate' . $ncsn2]['padding'] = 1;
                $page['cate' . $ncsn2]['title'] = $myts->htmlSpecialChars($nc_title);
                $page['cate' . $ncsn2]['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn2}";

                //第三層底下的文章
                $sql3 = 'select nsn,news_title from ' . $xoopsDB->prefix('tad_news') . " where ncsn='$ncsn2' order by page_sort";
                $result3 = $xoopsDB->query($sql3);
                while (list($nsn, $news_title) = $xoopsDB->fetchRow($result3)) {
                    $page['page' . $nsn]['type'] = 'page';
                    $page['page' . $nsn]['padding'] = 2;
                    $page['page' . $nsn]['title'] = $myts->htmlSpecialChars($news_title);
                    $page['page' . $nsn]['url'] = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn2}&nsn={$nsn}";
                    $page['page' . $nsn]['nsn'] = $nsn;
                }
            }
        }
    }
    $block['pages'] = $page;
    $block['show_title'] = $options[1];
    $block['color'] = $options[2];
    $block['bgcolor'] = $options[3];
    $block['bg_css'] = $options[4];
    $block['text_css'] = $options[5];
    $block['now_nsn'] = (int) $_GET['nsn'];

    return $block;
}

//區塊編輯函式
function tadnews_page_menu_edit($options)
{
    $sub_cate = 1 == $options[0] ? 'checked' : '';
    $no_sub_cate = 0 == $options[0] ? 'checked' : '';
    $show_title = 0 != $options[1] ? 'checked' : '';
    $dont_show_title = 0 == $options[1] ? 'checked' : '';

    $MColorPicker = new MColorPicker('.color');
    $MColorPicker->render();

    $form = "
    <ol class='my-form'>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SUB_CATE . "</lable>
            <div class='my-content'>
                <input type='radio' name='options[0]' value='1' id='sub_cate' $sub_cate>" . _YES . "
                <input type='radio' name='options[0]' value='0' id='no_sub_cate' $no_sub_cate>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_SHOW_TITLE . "</lable>
            <div class='my-content'>
            <INPUT type='radio' name='options[1]' value='1' id='show_title' $show_title>" . _YES . "
            <INPUT type='radio' name='options[1]' value='0' id='dont_show_title' $dont_show_title>" . _NO . "
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[2]' value='{$options[2]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_COLOR . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input color' data-hex='true' name='options[3]' value='{$options[3]}' size=6>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_BG_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[4]'>{$options[4]}</textarea>
                <span class='my-example'><br>
                padding: 4px; border-radius: 5px;
                </span>
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_FONT_CSS . "</lable>
            <div class='my-content'>
                <textarea class='my-input' name='options[5]'>{$options[5]}</textarea>
                <span class='my-example'><br>
                font-size: 1.1em; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;
                </span>
            </div>
        </li>
    </ol>";

    return $form;
}
