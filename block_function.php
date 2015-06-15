<?php
//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

include_once XOOPS_ROOT_PATH . "/modules/tadtools/TadUpFiles.php";
$TadUpFiles = new TadUpFiles("tadnews");

define("_SEPARTE", "<div style=\"page-break-after: always;\"><span style=\"display: none;\">&nbsp;</span></div>");
define("_SEPARTE2", "--summary--");
define("_TADNEWS_FILE_DIR", XOOPS_ROOT_PATH . "/uploads/tadnews");
define("_TADNEWS_UP_FILE_PATH", XOOPS_ROOT_PATH . "/uploads/tadnews/file");
define("_TADNEWS_UP_FILE_URL", XOOPS_URL . "/uploads/tadnews/file");
define("_TADNEWS_NSP_THEMES_PATH", XOOPS_ROOT_PATH . "/uploads/tadnews/themes");
define("_TADNEWS_NSP_THEMES_URL", XOOPS_URL . "/uploads/tadnews/themes");
define("_TADNEWS_CATE_DIR", XOOPS_ROOT_PATH . "/uploads/tadnews/cate");
define("_TADNEWS_CATE_URL", XOOPS_URL . "/uploads/tadnews/cate");

define("_TAD_NEWS_ERROR_LEVEL", 1);

//取得所有類別標題
if (!function_exists("block_news_cate")) {
    function block_news_cate($selected = "")
    {
        global $xoopsDB;

        if (!empty($selected)) {
            $sc = explode(",", $selected);
        }

        $js = "<script>
            function bbv(){
              i=0;
              var arr = new Array();";

        $sql    = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " where not_news!='1' order by sort";
        $result = $xoopsDB->query($sql);
        $option = "";
        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {

            $js .= "if(document.getElementById('c{$ncsn}').checked){
       arr[i] = document.getElementById('c{$ncsn}').value;
       i++;
      }";
            $ckecked = (in_array($ncsn, $sc)) ? "checked" : "";
            $option .= "<span style='white-space:nowrap;'><input type='checkbox' id='c{$ncsn}' value='{$ncsn}' class='bbv' onChange=bbv() $ckecked><label for='c{$ncsn}'>$nc_title</label></span> ";
        }

        $js .= "document.getElementById('bb').value=arr.join(',');
    }
    </script>";

        $main['js']   = $js;
        $main['form'] = $option;
        return $main;
    }
}

//取得所有類別標題
if (!function_exists("get_all_news_cate")) {
    function get_all_news_cate()
    {
        global $xoopsDB;
        $sql    = "select ncsn,nc_title from " . $xoopsDB->prefix("tad_news_cate") . " order by sort";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, show_error($sql));

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $data[$ncsn] = $nc_title;
        }
        return $data;
    }
}

//錯誤顯示方式
if (!function_exists("show_error")) {
    function show_error($sql = "")
    {
        if (_TAD_NEWS_ERROR_LEVEL == 1) {
            return mysql_error() . "<p>$sql</p>";
        } elseif (_TAD_NEWS_ERROR_LEVEL == 2) {
            return mysql_error();
        } elseif (_TAD_NEWS_ERROR_LEVEL == 3) {
            return "sql error";
        }
        return;
    }
}
