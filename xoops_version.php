<?php
//---基本設定---//
$modversion = [];
$language = isset($xoopsConfig['language']) ? $xoopsConfig['language'] : 'tchinese_utf8';
//---模組基本資訊---//
$modversion['name'] = _MI_TADNEWS_NAME;
$modversion['version'] = 4.20;
$modversion['description'] = _MI_TADNEWS_DESC;
$modversion['author'] = 'Tad (tad0616@gmail.com)';
$modversion['credits'] = 'geek01';
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$language}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2020-03-31';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TADNEWS_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = 'Tad';
$modversion['min_php'] = '5.4';
$modversion['min_xoops'] = '2.5';
$modversion['min_db'] = ['mysql' => '5.0.7', 'mysqli' => '5.0.7'];

//---paypal資訊---//
$modversion['paypal'] = [];
$modversion['paypal']['business'] = 'tad0616@gmail.com';
$modversion['paypal']['item_name'] = 'Donation : ' . _MI_TADNEWS_DESC;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.{$language}.sql";
$modversion['tables'][] = 'tad_news';
$modversion['tables'][] = 'tad_news_cate';
$modversion['tables'][] = 'tadnews_files_center';
$modversion['tables'][] = 'tad_news_paper';
$modversion['tables'][] = 'tad_news_paper_setup';
$modversion['tables'][] = 'tad_news_paper_email';
$modversion['tables'][] = 'tad_news_sign';
$modversion['tables'][] = 'tad_news_paper_send_log';
$modversion['tables'][] = 'tad_news_tags';
$modversion['tables'][] = 'tadnews_rank';
$modversion['tables'][] = 'tadnews_data_center';

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

global $xoopsModuleConfig, $xoopsUser;
if (isset($xoopsModuleConfig['show_submenu'])) {
    if ('1' == $xoopsModuleConfig['show_submenu']) {
        if ($xoopsUser) {
            $modversion['sub'][1]['name'] = _MI_TADNEWS_ADMENU2;
            $modversion['sub'][1]['url'] = 'post.php';
        }
        if ('1' == $xoopsModuleConfig['use_archive']) {
            $modversion['sub'][2]['name'] = _MI_TADNEWS_ARCHIVE;
            $modversion['sub'][2]['url'] = 'archive.php';
        }
        if ('1' == $xoopsModuleConfig['use_newspaper']) {
            $modversion['sub'][3]['name'] = _MI_TADNEWS_NEWSPAPER;
            $modversion['sub'][3]['url'] = 'newspaper.php';
        }
    }
}

$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';

//---樣板設定---//
$i = 0;
$modversion['templates'][$i]['file'] = 'tadnews_list.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_list.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_news.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_news.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_rss.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_rss.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_post.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_post.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_archive.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_archive.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_page.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_page.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_index_summary.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_index_summary.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_index_cate.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_index_cate.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_newspaper.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_newspaper.tpl';

$i++;
$modversion['templates'][$i]['file'] = 'tadnews_sign.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_sign.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_my_news.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_my_news.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_adm_main.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_page.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_adm_page.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_newspaper.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_adm_newspaper.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_tag.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_adm_tag.tpl';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_import.tpl';
$modversion['templates'][$i]['description'] = 'tadnews_adm_import.tpl';

//---評論設定---//
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'nsn';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.php';
$modversion['search']['func'] = 'tadnews_search';

//---區塊設定---//
$i = 1;
$modversion['blocks'][$i]['file'] = 'tadnews_cate.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC1;
$modversion['blocks'][$i]['show_func'] = 'tadnews_cate_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_cate.tpl';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_content_block.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC2;
$modversion['blocks'][$i]['show_func'] = 'tadnews_content_block_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_content_block.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_content_block_edit';
$modversion['blocks'][$i]['options'] = '5|100|color:gray;font-size: 0.8em;margin-top:3px;line-height:150%;|0|1|width:80px;height:60px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0|';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_re_block.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC3;
$modversion['blocks'][$i]['show_func'] = 'tadnews_b_show_3';
$modversion['blocks'][$i]['template'] = 'tadnews_block_re_block.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_re_edit';
$modversion['blocks'][$i]['options'] = '10|160';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_newspaper.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC4;
$modversion['blocks'][$i]['show_func'] = 'tadnews_newspaper';
$modversion['blocks'][$i]['template'] = 'tadnews_block_newspaper.tpl';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_newspaper_list.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME5;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC5;
$modversion['blocks'][$i]['show_func'] = 'tadnews_newspaper_list';
$modversion['blocks'][$i]['template'] = 'tadnews_block_newspaper_list.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_newspaper_list_edit';
$modversion['blocks'][$i]['options'] = '10';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_cate_news.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME6;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC6;
$modversion['blocks'][$i]['show_func'] = 'tadnews_cate_news';
$modversion['blocks'][$i]['template'] = 'tadnews_block_cate_news.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_cate_news_edit';
$modversion['blocks'][$i]['options'] = '|10|1|0|100|color:gray;font-size: 0.8em;margin-top:3px;line-height:150%;';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_page.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME7;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC7;
$modversion['blocks'][$i]['show_func'] = 'tadnews_page';
$modversion['blocks'][$i]['template'] = 'tadnews_block_page.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_page_edit';
$modversion['blocks'][$i]['options'] = '|160|1em';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_focus_news.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME8;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC8;
$modversion['blocks'][$i]['show_func'] = 'tadnews_focus_news';
$modversion['blocks'][$i]['template'] = 'tadnews_block_focus_news.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_focus_news_edit';
$modversion['blocks'][$i]['options'] = '|full';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_my_page.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME9;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC9;
$modversion['blocks'][$i]['show_func'] = 'tadnews_my_page';
$modversion['blocks'][$i]['template'] = 'tadnews_block_my_page.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_my_page_edit';
$modversion['blocks'][$i]['options'] = '';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_list_content_block.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME10;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC10;
$modversion['blocks'][$i]['show_func'] = 'tadnews_list_content_block_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_list_content_block.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_list_content_block_edit';
$modversion['blocks'][$i]['options'] = '5|100|color:gray;font-size: 0.8em;margin-top:3px;line-height:150%;|0|1|width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0||list|0|0';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_table_content_block.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME11;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC11;
$modversion['blocks'][$i]['show_func'] = 'tadnews_table_content_block_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_table_content_block.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_table_content_block_edit';
$modversion['blocks'][$i]['options'] = '6|1|start_day|news_title|uid|ncsn|counter|0||0';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_slidernews.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME13;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC13;
$modversion['blocks'][$i]['show_func'] = 'tadnews_slidernews_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_slidernews.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_slidernews_edit';
$modversion['blocks'][$i]['options'] = '670|250|5|90|';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_slidernews2.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_BNAME14;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC14;
$modversion['blocks'][$i]['show_func'] = 'tadnews_slidernews2_show';
$modversion['blocks'][$i]['template'] = 'tadnews_block_slidernews2.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_slidernews2_edit';
$modversion['blocks'][$i]['options'] = '5|90|ResponsiveSlides|';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_marquee.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_MARQUEE;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_MARQUEE_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_marquee';
$modversion['blocks'][$i]['template'] = 'tadnews_block_marquee.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_marquee_edit';
$modversion['blocks'][$i]['options'] = '5|0|down|5000||';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_covered.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_COVERED;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_COVERED_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_covered';
$modversion['blocks'][$i]['template'] = 'tadnews_block_covered.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_covered_edit';
$modversion['blocks'][$i]['options'] = '3|2|66|font-size: 0.8125em ;color: gray; line-height: 1.5; font-family:PMingLiU;|';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_page_list.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_PAGE_LIST;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_PAGE_LIST_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_page_list';
$modversion['blocks'][$i]['template'] = 'tadnews_block_page_list.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_page_list_edit';
$modversion['blocks'][$i]['options'] = '|#9ea200|1|1|#ffffff|padding: 4px; border-radius: 5px;|font-size: 1.1em; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_tab_news.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_TAB_NEWS;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_TAB_NEWS_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_tab_news';
$modversion['blocks'][$i]['template'] = 'tadnews_block_tab_news.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_tab_news_edit';
$modversion['blocks'][$i]['options'] = '|10|default|#FFFFFF|#E0D9D9|#9C905C|#9C905C|0|16';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_tag_news.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_TAG_NEWS;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_TAG_NEWS_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_tag_news';
$modversion['blocks'][$i]['template'] = 'tadnews_block_tag_news.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_tag_news_edit';
$modversion['blocks'][$i]['options'] = '|10|default|#FFFFFF|#E0D9D9|#9C905C|#9C905C|0|16';

$i++;
$modversion['blocks'][$i]['file'] = 'tadnews_page_menu.php';
$modversion['blocks'][$i]['name'] = _MI_TADNEWS_PAGE_MENU . '[hide]';
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_PAGE_MENU_DESC;
$modversion['blocks'][$i]['show_func'] = 'tadnews_page_menu';
$modversion['blocks'][$i]['template'] = 'tadnews_block_page_menu.tpl';
$modversion['blocks'][$i]['edit_func'] = 'tadnews_page_menu_edit';
$modversion['blocks'][$i]['options'] = '1|1|#dbff40|#5e6b00|padding: 4px; border-radius: 5px;|font-size: 1.1em; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;';

//---偏好設定---//
$i = 1;
$modversion['config'][$i]['name'] = 'show_num';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_TITLE1';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_DESC1';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;

$i++;
$modversion['config'][$i]['name'] = 'show_mode';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_SHOW_MODE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'summary';
$modversion['config'][$i]['options'] = ['_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list', '_MI_TADNEWS_SHOW_MODE_OPT3' => 'cate'];

$i++;
$modversion['config'][$i]['name'] = 'cate_show_mode';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_CATE_SHOW_MODE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CATE_SHOW_MODE_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'summary';
$modversion['config'][$i]['options'] = ['_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list'];

$i++;
$modversion['config'][$i]['name'] = 'show_bbcode';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_SHOW_BB';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_BB_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'cate_pic_width';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_CATE_PIC_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CATE_PIC_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 400;

$i++;
$modversion['config'][$i]['name'] = 'pic_width';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_PIC_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_PIC_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 550;

$i++;
$modversion['config'][$i]['name'] = 'thumb_width';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_THUMB_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_THUMB_WIDTH_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 400;

$i++;
$modversion['config'][$i]['name'] = 'use_newspaper';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_USE_NEWSPAPER';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_USE_NEWSPAPER_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'use_archive';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_USE_USE_ARCHIVE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_USE_USE_ARCHIVE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'show_submenu';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_SHOW_SUBMENU';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_SUBMENU_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'download_after_read';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_DOWNLOAD_AFTER_READ';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_DOWNLOAD_AFTER_READ_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'creat_cate_group';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_CREAT_CATE_GROUP';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CREAT_CATE_GROUP_DESC';
$modversion['config'][$i]['formtype'] = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = [1];

$i++;
$modversion['config'][$i]['name'] = 'use_top_group';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_USE_TOP_GROUP';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_USE_TOP_GROUP_DESC';
$modversion['config'][$i]['formtype'] = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = [];

$i++;
$modversion['config'][$i]['name'] = 'top_max_day';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_TOP_MAX_DAY';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_TOP_MAX_DAY_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 14;

$i++;
$modversion['config'][$i]['name'] = 'summary_lengths';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_SUMMARY_LENGTHS';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SUMMARY_LENGTHS_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 100;

$i++;
$modversion['config'][$i]['name'] = 'facebook_comments_width';
$modversion['config'][$i]['title'] = '_MI_FBCOMMENT_TITLE';
$modversion['config'][$i]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'use_pda';
$modversion['config'][$i]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][$i]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'use_social_tools';
$modversion['config'][$i]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][$i]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '1';

$i++;
$modversion['config'][$i]['name'] = 'use_star_rating';
$modversion['config'][$i]['title'] = '_MI_STAR_RATING_TITLE';
$modversion['config'][$i]['description'] = '_MI_STAR_RATING_DESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'cover_pic_css';
$modversion['config'][$i]['title'] = '_MI_COVER_PIC_CSS';
$modversion['config'][$i]['description'] = '_MI_COVER_PIC_CSS_DESC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = 'width:200px; height:150px; border:1px solid #909090; background-position:left top; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;';

$i++;
$modversion['config'][$i]['name'] = 'upload_deny';
$modversion['config'][$i]['title'] = '_MI_TADNEWS_UPLOAD_DENY';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_UPLOAD_DENY_DESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = "";
