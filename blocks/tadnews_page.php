<?php

//區塊主函式 (顯示指定的自訂類別文章)
function tadnews_page($options)
{
    global $xoopsDB;
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/dtree.php")) {
        redirect_header("index.php", 3, _MB_NEED_TADTOOLS);
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/dtree.php";

    if (empty($options[0])) {
        $sql        = "SELECT ncsn FROM " . $xoopsDB->prefix("tad_news_cate") . " WHERE not_news='1' AND of_ncsn=0 ORDER BY ncsn LIMIT 0,1";
        $result     = $xoopsDB->query($sql);
        list($ncsn) = $xoopsDB->fetchRow($result);
    } else {
        $ncsn = (int) $options[0];
    }

    $sql                             = "select ncsn,of_ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where not_news='1' and `ncsn`='{$ncsn}'";
    $result                          = $xoopsDB->query($sql);
    list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result);

    $home['sn']    = $ncsn;
    $home['title'] = $nc_title;
    $home['url']   = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}";

    $page = block_get_page_cate($ncsn);

    $dtree = new dtree("tadnews_mypage_tree{$ncsn}", $home, $page['title'], $page['of_ncsn'], $page['url']);
    $block = $dtree->render($options[2]);
    return $block;
}

//區塊編輯函式
function tadnews_page_edit($options)
{
    $cate = block_get_all_not_news_cate(0, $options[0]);

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
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM1 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[1]' value='{$options[1]}' size=6>px
            </div>
        </li>
        <li class='my-row'>
            <lable class='my-label'>" . _MB_TADNEWS_PAGE_EDIT_BITEM2 . "</lable>
            <div class='my-content'>
                <input type='text' class='my-input' name='options[2]' value='{$options[2]}' size=6>
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

//樹狀選項
if (!function_exists("block_get_page_cate")) {
    function block_get_page_cate($the_ncsn = 0, $i = 10000)
    {
        global $xoopsDB;

        $sql = "select ncsn,of_ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where ncsn='$the_ncsn' or of_ncsn='$the_ncsn' order by sort";
        //die($sql);
        $result = $xoopsDB->query($sql);
        $myts   = MyTextSanitizer::getInstance();
        while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            //第一層底下的目錄
            if ($the_ncsn != $ncsn) {
                $page['title'][$ncsn]   = $nc_title;
                $page['of_ncsn'][$ncsn] = $of_ncsn;
                $page['url'][$ncsn]     = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}";

                $the_ncsn = $ncsn;
                $sql3     = "select ncsn,of_ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where ncsn='$the_ncsn' or of_ncsn='$the_ncsn' order by sort";
                $result3  = $xoopsDB->query($sql3);
                while (list($ncsn, $of_ncsn, $nc_title) = $xoopsDB->fetchRow($result3)) {
                    //第二層底下的目錄
                    if ($the_ncsn != $ncsn) {
                        $page['title'][$ncsn]   = $nc_title;
                        $page['of_ncsn'][$ncsn] = $of_ncsn;
                        $page['url'][$ncsn]     = XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}";
                    }

                    //第二層底下的文章
                    $sql4    = "select nsn,ncsn,news_title from " . $xoopsDB->prefix("tad_news") . " where ncsn='$ncsn' order by page_sort";
                    $result4 = $xoopsDB->query($sql4);
                    $j       = $ncsn * 10000;
                    while (list($nsn, $ncsn, $news_title) = $xoopsDB->fetchRow($result4)) {
                        $myts->htmlSpecialChars($news_title);
                        $page['title'][$j]   = $news_title;
                        $page['of_ncsn'][$j] = $ncsn;
                        $page['url'][$j]     = XOOPS_URL . "/modules/tadnews/page.php?nsn={$nsn}";
                        $j++;
                    }
                }
            }

            //第一層底下的文章
            $sql2    = "select nsn,ncsn,news_title from " . $xoopsDB->prefix("tad_news") . " where ncsn='$ncsn' order by page_sort";
            $result2 = $xoopsDB->query($sql2);
            while (list($nsn, $ncsn, $news_title) = $xoopsDB->fetchRow($result2)) {
                $myts->htmlSpecialChars($news_title);
                $page['title'][$i]   = $news_title;
                $page['of_ncsn'][$i] = $ncsn;
                $page['url'][$i]     = XOOPS_URL . "/modules/tadnews/page.php?nsn={$nsn}";
                $i++;
            }
        }

        return $page;
    }
}
