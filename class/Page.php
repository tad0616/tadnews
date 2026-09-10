<?php

namespace XoopsModules\Tadnews;

use XoopsModules\Tadtools\BootstrapTable;
use XoopsModules\Tadtools\Utility;

/**
 * 單頁（tad_news.news_kind = 'page'）：顯示、列表、選單掛載與頁籤排序
 * 由 page.php 搬入（2026-08-14），行為未變更。
 */
class Page
{
    //取得單頁標題（供麵包屑使用）
    public static function get_title($nsn = '')
    {
        global $xoopsDB;
        $sql    = 'SELECT `news_title` FROM `' . $xoopsDB->prefix('tad_news') . '` WHERE `nsn`=?';
        $result = Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        list($news_title) = $xoopsDB->fetchRow($result);

        return $news_title;
    }

    //顯示單一新聞
    public static function show_page($nsn = '')
    {
        global $Tadnews;

        $Tadnews->set_view_nsn($nsn);
        $Tadnews->set_news_kind('page');
        $Tadnews->set_cover(true, 'db');
        $Tadnews->set_summary('full');
        $Tadnews->get_news();

        BootstrapTable::render();
    }

    //列出所有tad_news資料
    public static function list_tad_all_pages($the_ncsn = 0)
    {
        global $xoopsTpl, $xoopsDB, $Tadnews;

        Utility::get_jquery(true);
        $Tadnews->set_news_kind('page');
        $Tadnews->set_show_num('none');
        $Tadnews->set_view_ncsn($the_ncsn);
        $Tadnews->get_cate_news();

        $link_cate_sn_arr = [];
        $moduleHandler    = xoops_getHandler('module');
        $TadThemesModule  = $moduleHandler->getByDirname('tad_themes');
        if ($TadThemesModule) {
            $sql    = 'SELECT `link_cate_sn` FROM `' . $xoopsDB->prefix('tad_themes_menu') . '` WHERE `link_cate_name`=?';
            $result = Utility::query($sql, 's', ['tadnews_page_cate']) or Utility::web_error($sql, __FILE__, __LINE__);

            while (list($link_cate_sn) = $xoopsDB->fetchRow($result)) {
                $link_cate_sn_arr[] = $link_cate_sn;
            }
        }
        $xoopsTpl->assign('link_cate_sn_arr', $link_cate_sn_arr);
        $xoopsTpl->assign('ok_cat', Tools::chk_user_cate_power('post'));

        //工具列：單一分類看該分類設定（tool=0 才隱藏），列表全部分類時預設顯示
        $cate  = $Tadnews->get_tad_news_cate($the_ncsn);
        $setup = isset($cate['setup']) ? $cate['setup'] : '';
        $xoopsTpl->assign('cate_set_tool', false === mb_strpos($setup, 'tool=0'));
    }

    public static function add_to_menu($ncsn = '')
    {
        global $xoopsDB;

        $sql    = 'SELECT * FROM `' . $xoopsDB->prefix('tad_news_cate') . '` WHERE `ncsn`=?';
        $result = Utility::query($sql, 'i', [$ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        $cate = $xoopsDB->fetchArray($result);

        $moduleHandler   = xoops_getHandler('module');
        $TadThemesModule = $moduleHandler->getByDirname('tad_themes');
        if ($TadThemesModule) {
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('tad_themes_menu') . '` (`of_level`, `position`, `itemname`, `itemurl`, `status`, `target`, `icon`, `link_cate_name`, `link_cate_sn`, `read_group`) VALUES (0, 1, ?, ?, 1, "_self", "", "tadnews_page_cate", ?, "")';
            Utility::query($sql, 'ssi', [$cate['nc_title'], XOOPS_URL . '/modules/tadnews/page.php?ncsn=' . $ncsn, $ncsn]) or Utility::web_error($sql, __FILE__, __LINE__);

            //取得最後新增資料的流水編號
            $xoopsDB->getInsertId();
        }
    }

    //頁籤排序
    public static function tabs_sort($ncsn, $nsn)
    {
        global $xoopsDB, $xoopsTpl, $Tadnews;

        $sql    = 'SELECT `data_value`, `data_sort` FROM `' . $xoopsDB->prefix('tadnews_data_center') . '` WHERE `col_name` = \'nsn\' AND `col_sn` =? AND `data_name` = \'tab_title\' ORDER BY `data_sort`';
        $result = Utility::query($sql, 'i', [$nsn]) or Utility::web_error($sql, __FILE__, __LINE__);

        $myts    = \MyTextSanitizer::getInstance();
        $tab_div = [];
        while (list($data_value, $data_sort) = $xoopsDB->fetchRow($result)) {
            $tab_div[$data_sort] = $data_value;
        }
        $xoopsTpl->assign('ncsn', $ncsn);
        $xoopsTpl->assign('nsn', $nsn);
        $xoopsTpl->assign('tab_div', $tab_div);
        Utility::get_jquery(true);
        $Tadnews->set_view_nsn($nsn);
        $Tadnews->set_news_kind('page');
        $Tadnews->set_cover(true, 'db');
        $Tadnews->set_summary('full');
        $Tadnews->get_news();
    }

    //儲存頁籤排序（AJAX：save_sort.php?op=sort_tabs）
    public static function save_tabs_sort($nsn, array $updateRecordsArray = [])
    {
        global $xoopsDB;

        $sort = 1;
        foreach ($updateRecordsArray as $data_sort) {
            $data_sort = (int) $data_sort;
            $sql       = 'UPDATE `' . $xoopsDB->prefix('tadnews_data_center') . '`
            SET `data_sort` = ?
            WHERE `col_name` = ?
            AND `col_sn` = ?
            AND `data_sort` = ?
            AND (`data_name` = ? OR `data_name` = ?)';

            $params = ["{$sort}000", 'nsn', $nsn, $data_sort, 'tab_title', 'tab_content'];
            Utility::query($sql, 'ssiiss', $params) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

            $sort++;
        }

        $sql = 'UPDATE `' . $xoopsDB->prefix('tadnews_data_center') . '` SET `data_sort`=`data_sort`/1000 WHERE `col_name`=? AND `col_sn`=? AND (`data_name`=? OR `data_name`=?)';
        Utility::query($sql, 'siss', ['nsn', $nsn, 'tab_title', 'tab_content']) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

        $sql    = 'SELECT `data_name`, `data_value`, `data_sort` FROM `' . $xoopsDB->prefix('tadnews_data_center') . '` WHERE `col_name`=? AND `col_sn`=? AND (`data_name`=? OR `data_name`=?) ORDER BY `data_sort`';
        $result = Utility::query($sql, 'siss', ['nsn', $nsn, 'tab_title', 'tab_content']) or Utility::web_error($sql, __FILE__, __LINE__);

        $myts          = \MyTextSanitizer::getInstance();
        $tab_title_div = $tab_content_div = '';
        while (list($data_name, $data_value, $data_sort) = $xoopsDB->fetchRow($result)) {
            if ($data_sort == 0) {
                $news_content = $data_value;
            } else {
                if ('tab_title' === $data_name) {
                    $tab_title_div .= "<li>$data_value</li>";
                } else {
                    $tab_content_div .= "
                <div>
                    {$data_value}
                </div>";
                }
            }
        }

        $tabs_content = "
            <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/css/easy-responsive-tabs.css' type='text/css'>
            <link rel='stylesheet' href='" . XOOPS_URL . "/modules/tadnews/css/easy-responsive-tabs.css' type='text/css'>
            <script src='" . XOOPS_URL . "/modules/tadtools/Easy-Responsive-Tabs/js/easyResponsiveTabs.js' type='text/javascript'></script>
            {$news_content}
            <div id='PageTab'>
            <ul class='resp-tabs-list vert'>
                {$tab_title_div}
            </ul>
            <div class='resp-tabs-container vert'>
                {$tab_content_div}
            </div>
        </div>
        <script type='text/javascript'>
            $(document).ready(function(){
                $('#PageTab').easyResponsiveTabs({
                    tabidentify: 'vert',
                    type: 'default', //Types: default, vertical, accordion
                    width: 'auto',
                    fit: true,
                    closed: false
                });
            });
        </script>
        ";

        $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `news_content`=? WHERE `nsn`=?';
        Utility::query($sql, 'si', [$tabs_content, $nsn]) or Utility::web_error($sql, __FILE__, __LINE__);
    }

    //儲存單頁排序（AJAX：save_sort.php）
    public static function save_page_sort(array $updateRecordsArray = [])
    {
        global $xoopsDB;

        $sort = 1;
        foreach ($updateRecordsArray as $nsn) {
            $nsn = (int) $nsn;
            $sql = 'UPDATE `' . $xoopsDB->prefix('tad_news') . '` SET `page_sort`=? WHERE `nsn`=?';
            Utility::query($sql, 'ii', [$sort, $nsn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')');

            $sort++;
        }
    }
}
