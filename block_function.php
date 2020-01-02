<?php
use XoopsModules\Tadtools\Utility;

define('_SEPARTE', '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>');
define('_SEPARTE2', '--summary--');

//取得所有類別標題
if (!function_exists('block_news_cate')) {
    function block_news_cate($selected = '')
    {
        global $xoopsDB;

        if (!empty($selected)) {
            $sc = explode(',', $selected);
        }

        $js = '<script>
            function bbv(){
            i=0;
            var arr = new Array();';

        $sql = 'SELECT ncsn,nc_title FROM ' . $xoopsDB->prefix('tad_news_cate') . " WHERE not_news!='1' ORDER BY sort";
        $result = $xoopsDB->query($sql);
        $option = '';
        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            $js .= "if(document.getElementById('c{$ncsn}').checked){
            arr[i] = document.getElementById('c{$ncsn}').value;
            i++;
            }";
            $ckecked = (in_array($ncsn, $sc)) ? 'checked' : '';
            $option .= "<span style='white-space:nowrap;'><input type='checkbox' id='c{$ncsn}' value='{$ncsn}' class='bbv' onChange=bbv() $ckecked><label for='c{$ncsn}'>$nc_title</label></span> ";
        }

        $js .= "document.getElementById('bb').value=arr.join(',');
    }
    </script>";

        $main['js'] = $js;
        $main['form'] = $option;

        return $main;
    }
}

//取得所有類別標題
if (!function_exists('get_all_news_cate')) {
    function get_all_news_cate($ncsn_arr_str = '')
    {
        global $xoopsDB;
        $ncsn_arr = [];
        if ($ncsn_arr_str) {
            $ncsn_arr = explode(',', $ncsn_arr_str);
        }

        $sql = 'SELECT ncsn,nc_title FROM ' . $xoopsDB->prefix('tad_news_cate') . ' ORDER BY sort';
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            if (empty($ncsn_arr_str) or in_array($ncsn, $ncsn_arr)) {
                $data[$ncsn] = $nc_title;
            }
        }

        return $data;
    }
}

//取得所有標籤
if (!function_exists('get_all_news_tag')) {
    function get_all_news_tag()
    {
        global $xoopsDB;
        $sql = 'SELECT tag_sn,tag FROM ' . $xoopsDB->prefix('tad_news_tags') . '';
        $result = $xoopsDB->queryF($sql);
        $data = [];
        while (list($tag_sn, $tag) = $xoopsDB->fetchRow($result)) {
            $data[$tag_sn] = $tag;
        }

        return $data;
    }
}

//取得所有標籤
if (!function_exists('block_news_tags')) {
    function block_news_tags($selected = '')
    {
        global $xoopsDB;

        $sc = [];
        if (!empty($selected)) {
            $sc = explode(',', $selected);
        }

        $js = '<script>
            function bbv(){
              i=0;
              var arr = new Array();';

        $sql = 'SELECT tag_sn,tag FROM ' . $xoopsDB->prefix('tad_news_tags') . " WHERE enable='1' ";
        $result = $xoopsDB->query($sql);
        $option = '';
        while (list($tag_sn, $tag) = $xoopsDB->fetchRow($result)) {
            $js .= "if(document.getElementById('c{$tag_sn}').checked){
                arr[i] = document.getElementById('c{$tag_sn}').value;
                i++;
            }";
            $ckecked = in_array($tag_sn, $sc) ? 'checked' : '';
            $option .= "<span style='white-space:nowrap;'><input type='checkbox' id='c{$tag_sn}' value='{$tag_sn}' class='bbv' onChange=bbv() $ckecked><label for='c{$tag_sn}'>$tag</label></span> ";
            $tags[$tag_sn] = $tag;
        }

        $js .= "document.getElementById('bb').value=arr.join(',');
    }
    </script>";

        $main['js'] = $js;
        $main['form'] = $option;
        $main['tags'] = $tags;

        return $main;
    }
}
