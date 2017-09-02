<?php
include_once XOOPS_ROOT_PATH . "/modules/tadnews/class/tadnews.php";
$tadnews = new tadnews();
include_once "block_function.php";

//取得路徑
function get_tadnews_cate_path($the_ncsn = "", $include_self = true)
{
    global $xoopsDB, $tadnews;

    $arr[0]['ncsn']     = "0";
    $arr[0]['nc_title'] = "<i class='fa fa-home'></i>";
    $arr[0]['sub']      = get_tadnews_sub_cate(0);
    if (!empty($the_ncsn)) {
        $tbl = $xoopsDB->prefix("tad_news_cate");
        $sql = "SELECT t1.ncsn AS lev1, t2.ncsn as lev2, t3.ncsn as lev3, t4.ncsn as lev4, t5.ncsn as lev5, t6.ncsn as lev6, t7.ncsn as lev7
            FROM `{$tbl}` t1
            LEFT JOIN `{$tbl}` t2 ON t2.of_ncsn = t1.ncsn
            LEFT JOIN `{$tbl}` t3 ON t3.of_ncsn = t2.ncsn
            LEFT JOIN `{$tbl}` t4 ON t4.of_ncsn = t3.ncsn
            LEFT JOIN `{$tbl}` t5 ON t5.of_ncsn = t4.ncsn
            LEFT JOIN `{$tbl}` t6 ON t6.of_ncsn = t5.ncsn
            LEFT JOIN `{$tbl}` t7 ON t7.of_ncsn = t6.ncsn
            WHERE t1.of_ncsn = '0'";

        $result = $xoopsDB->query($sql) or web_error($sql);
        while ($all = $xoopsDB->fetchArray($result)) {
            if (in_array($the_ncsn, $all)) {
                foreach ($all as $ncsn) {
                    if (!empty($ncsn)) {
                        if (!$include_self and $ncsn == $the_ncsn) {
                            break;
                        }
                        $arr[$ncsn]        = $tadnews->get_tad_news_cate($ncsn);
                        $arr[$ncsn]['sub'] = get_tadnews_sub_cate($ncsn);
                        if ($ncsn == $the_ncsn) {
                            break;
                        }
                    }
                }
                //$main.="<br>";
                break;
            }
        }
    }
    return $arr;
}

function get_tadnews_sub_cate($ncsn = "0")
{
    global $xoopsDB;
    $sql = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where of_ncsn='{$ncsn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $ncsn_arr = "";
    while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
        $ncsn_arr[$ncsn] = $nc_title;
    }
    return $ncsn_arr;
}

//取得電子報設定資料
function get_newspaper_set($nps_sn = "")
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;
    $sql = "select * from " . $xoopsDB->prefix("tad_news_paper_setup") . " where nps_sn='{$nps_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//取得電子報資料
function get_newspaper($npsn = "")
{
    global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsConfig;
    $sql = "select * from " . $xoopsDB->prefix("tad_news_paper") . " where npsn='{$npsn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//預覽電子報
function preview_newspaper($npsn = "")
{
    global $xoopsDB;
    if (empty($npsn)) {
        return;
    }

    $np  = get_newspaper($npsn);
    $sql = "select title,head,foot,themes from " . $xoopsDB->prefix("tad_news_paper_setup") . " where nps_sn='{$np['nps_sn']}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    list($title, $head, $foot, $themes) = $xoopsDB->fetchRow($result);

    $myts             = MyTextSanitizer::getInstance();
    $title            = $myts->htmlSpecialChars($title);
    $np['np_title']   = $myts->htmlSpecialChars($np['np_title']);
    $np['np_content'] = $myts->displayTarea($np['np_content'], 1, 1, 1, 1, 0);

    $head = str_replace('{N}', $np['number'], $head);
    $head = str_replace('{D}', substr($np['np_date'], 0, 10), $head);
    $head = str_replace('{T}', $np['np_title'], $head);

    $filename = _TADNEWS_NSP_THEMES_PATH . "/{$themes}/index.html";
    // die('filename: ' . $filename);
    $handle   = fopen($filename, "rb");
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);
    $main = str_replace("{TNP_THEME}", _TADNEWS_NSP_THEMES_URL . "/{$themes}/", $contents);
    $main = str_replace("{TNP_CSS}", "", $main);
    $main = str_replace("{TNP_TITLE}", $title, $main);
    $char = _CHARSET;
    $main = str_replace("{TNP_CODE}", $char, $main);
    $main = str_replace("{TNP_HEAD}", $head, $main);
    $main = str_replace("{TNP_FOOT}", $foot, $main);
    $main = str_replace("{TNP_URL}", XOOPS_URL . "/modules/tadnews/newspaper.php?npsn={$npsn}", $main);
    $main = str_replace("{TNP_CONTENT}", $np['np_content'], $main);
    return $main;
}
