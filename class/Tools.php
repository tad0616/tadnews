<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\Utility;

class Tools
{
    public static function get_pages_recursive($ncsn, $padding, $include_subcategories)
    {
        global $xoopsDB;
        $pages = [];
        $myts = \MyTextSanitizer::getInstance();

        // 獲取當前分類下的文章
        $sql = 'SELECT `nsn`, `news_title` FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `ncsn`=? ORDER BY `page_sort`';
        $result = Utility::query($sql, 'i', [$ncsn]);

        while ($row = $xoopsDB->fetchRow($result)) {
            $nsn = $row[0];
            $news_title = $row[1];
            $pages["page{$nsn}"] = [
                'type' => 'page',
                'padding' => $padding,
                'title' => $myts->htmlSpecialChars($news_title),
                'url' => XOOPS_URL . "/modules/tadnews/page.php?ncsn={$ncsn}&nsn={$nsn}",
                'nsn' => $nsn,
                'ncsn' => $ncsn,
            ];
        }

        // 如果需要包含子分類
        if ($include_subcategories) {
            $sql = 'SELECT `ncsn`, `nc_title`, `of_ncsn` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `of_ncsn`=? ORDER BY `sort`';
            $result = Utility::query($sql, 'i', [$ncsn]);

            while ($row = $xoopsDB->fetchRow($result)) {
                $sub_ncsn = $row[0];
                $nc_title = $row[1];
                $of_ncsn = $row[2];
                $pages["cate{$sub_ncsn}"] = [
                    'type' => 'cate',
                    'padding' => $padding,
                    'title' => $myts->htmlSpecialChars($nc_title),
                    'url' => XOOPS_URL . "/modules/tadnews/page.php?ncsn={$sub_ncsn}",
                    'ncsn' => $sub_ncsn,
                    'of_ncsn' => $of_ncsn,
                ];

                // 遞迴獲取子分類的頁面
                $sub_pages = self::get_pages_recursive($sub_ncsn, $padding + 1, $include_subcategories);
                $pages = array_merge($pages, $sub_pages);
            }
        }

        return $pages;
    }

    //取得所有類別標題
    public static function block_news_cate($selected = '')
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

        $sql = 'SELECT `ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `not_news`!=? ORDER BY `sort`';
        $result = Utility::query($sql, 's', [1]);

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

    //取得所有類別標題
    public static function get_all_news_cate($ncsn_arr_str = '')
    {
        global $xoopsDB;
        $ncsn_arr = [];
        if ($ncsn_arr_str) {
            $ncsn_arr = explode(',', $ncsn_arr_str);
        }

        $sql = 'SELECT `ncsn`, `nc_title` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` ORDER BY `sort`';
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $nc_title) = $xoopsDB->fetchRow($result)) {
            if (empty($ncsn_arr_str) or in_array($ncsn, $ncsn_arr)) {
                $data[$ncsn] = $nc_title;
            }
        }

        return $data;
    }

    //取得所有標籤
    public static function get_all_news_tag()
    {
        global $xoopsDB;
        $sql = 'SELECT `tag_sn`, `tag` FROM `' . $xoopsDB->prefix('tad_news_tags') . '`';
        $result = $xoopsDB->query($sql);

        $data = [];
        while (list($tag_sn, $tag) = $xoopsDB->fetchRow($result)) {
            $data[$tag_sn] = $tag;
        }

        return $data;
    }

    //取得所有標籤
    public static function block_news_tags($selected = '')
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

        $sql = 'SELECT `tag_sn`, `tag` FROM `' . $xoopsDB->prefix('tad_news_tags') . '` WHERE `enable`=?';
        $result = Utility::query($sql, 's', [1]) or Utility::web_error($sql, __FILE__, __LINE__);

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

    public static function block_get_page_cate($the_ncsn = 0)
    {
        $pages = self::get_pages_recursive($the_ncsn, 0, true);
        $result = [
            'title' => [],
            'of_ncsn' => [],
            'url' => [],
        ];

        $i = 10000;
        foreach ($pages as $key => $page) {
            if ($page['type'] === 'cate') {
                $ncsn = substr($key, 4);
                $result['title'][$ncsn] = $page['title'];
                $result['of_ncsn'][$ncsn] = isset($page['of_ncsn']) ? $page['of_ncsn'] : $the_ncsn;
                $result['url'][$ncsn] = $page['url'];
            } else {
                $result['title'][$i] = $page['title'];
                $result['of_ncsn'][$i] = $page['ncsn'];
                $result['url'][$i] = $page['url'];
                $i++;
            }
        }

        return $result;
    }

    //判斷目前登入者在哪些類別中有發表的權利
    public static function chk_user_cate_power($kind = 'post')
    {
        global $xoopsDB, $xoopsUser, $tadnews_adm;
        if (empty($xoopsUser)) {
            return false;
        }
        //判斷是否對該模組有管理權限
        if (!isset($tadnews_adm)) {
            $tadnews_adm = isset($xoopsUser) && \is_object($xoopsUser) ? $xoopsUser->isAdmin() : false;
        }
        if ($tadnews_adm) {
            $ok_cat[] = 0;
        }

        $col = ('post' === $kind) ? 'enable_post_group' : 'enable_group';

        //非管理員才要檢查
        $where = ($tadnews_adm) ? '' : "WHERE `{$col}` != ''";
        $sql = 'SELECT `ncsn`, `' . $col . '` FROM `' . $xoopsDB->prefix('tad_news_cate') . '` ' . $where;
        $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

        while (list($ncsn, $power) = $xoopsDB->fetchRow($result)) {
            if ($tadnews_adm or 'pass' === $kind) {
                $ok_cat[] = (int) $ncsn;
            } else {
                $power_array = explode(',', $power);
                foreach ($power_array as $gid) {
                    // $gid = (int) $gid;
                    if (in_array($gid, $xoopsUser->getGroups())) {
                        $ok_cat[] = (int) $ncsn;
                        break;
                    }
                }
            }
        }

        return $ok_cat;
    }

}
