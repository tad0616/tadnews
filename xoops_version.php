<?php
//---基本設定---//
$modversion = array();

//---模組基本資訊---//
$modversion['name']        = _MI_TADNEWS_NAME;
$modversion['version']     = 3.6;
$modversion['description'] = _MI_TADNEWS_DESC;
$modversion['author']      = 'Tad (tad0616@gmail.com)';
$modversion['credits']     = 'geek01';
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image']       = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname']     = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['release_date']        = '2015/06/10';
$modversion['module_website_url']  = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TADNEWS_WEB;
$modversion['module_status']       = 'release';
$modversion['author_website_url']  = 'http://tad0616.net/';
$modversion['author_website_name'] = 'Tad';
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = '2.5';
$modversion['min_db']              = array('mysql' => '5.0.7', 'mysqli' => '5.0.7');

//---paypal資訊---//
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'tad0616@gmail.com';
$modversion['paypal']['item_name']     = 'Donation : ' . _MI_TADNEWS_DESC;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.{$xoopsConfig['language']}.sql";
$modversion['tables'][1]        = "tad_news";
$modversion['tables'][2]        = "tad_news_cate";
$modversion['tables'][3]        = "tadnews_files_center";
$modversion['tables'][4]        = "tad_news_paper";
$modversion['tables'][5]        = "tad_news_paper_setup";
$modversion['tables'][6]        = "tad_news_paper_email";
$modversion['tables'][7]        = "tad_news_sign";
$modversion['tables'][8]        = "tad_news_paper_send_log";
$modversion['tables'][9]        = "tad_news_tags";
$modversion['tables'][10]       = "tadnews_rank";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu']  = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

global $xoopsModuleConfig, $xoopsUser;
if (isset($xoopsModuleConfig['show_submenu'])) {
    if ($xoopsModuleConfig['show_submenu'] == '1') {
        if ($xoopsUser) {
            $modversion['sub'][1]['name'] = _MI_TADNEWS_ADMENU2;
            $modversion['sub'][1]['url']  = "post.php";
        }
        if ($xoopsModuleConfig['use_archive'] == '1') {
            $modversion['sub'][2]['name'] = _MI_TADNEWS_ARCHIVE;
            $modversion['sub'][2]['url']  = "archive.php";
        }
        if ($xoopsModuleConfig['use_newspaper'] == '1') {
            $modversion['sub'][3]['name'] = _MI_TADNEWS_NEWSPAPER;
            $modversion['sub'][3]['url']  = "newspaper.php";
        }
    }
}

$modversion['onInstall']   = "include/onInstall.php";
$modversion['onUpdate']    = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---樣板設定---//
$i                                          = 0;
$modversion['templates'][$i]['file']        = 'tadnews_list.html';
$modversion['templates'][$i]['description'] = 'tadnews_list.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_list_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_list_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_news.html';
$modversion['templates'][$i]['description'] = 'tadnews_news.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_news_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_news_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_rss.html';
$modversion['templates'][$i]['description'] = 'tadnews_rss.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_rss_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_rss_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_post.html';
$modversion['templates'][$i]['description'] = 'tadnews_post.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_post_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_post_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_archive.html';
$modversion['templates'][$i]['description'] = 'tadnews_archive.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_archive_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_archive_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_page.html';
$modversion['templates'][$i]['description'] = 'tadnews_page.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_page_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_page_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_index_summary.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_summary.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_index_summary_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_summary_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_index_cate.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_cate.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_index_cate_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_cate_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_newspaper.html';
$modversion['templates'][$i]['description'] = 'tadnews_newspaper.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_newspaper_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_newspaper_b3.html';

$i++;
$modversion['templates'][$i]['file']        = 'tadnews_page_list.html';
$modversion['templates'][$i]['description'] = 'tadnews_page_list.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_page_list_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_page_list_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_sign.html';
$modversion['templates'][$i]['description'] = 'tadnews_sign.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_sign_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_sign_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_my_news.html';
$modversion['templates'][$i]['description'] = 'tadnews_my_news.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_my_news_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_my_news_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_main.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_main.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_main_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_main_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_page.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_page.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_page_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_page_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_newspaper.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_newspaper.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_newspaper_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_newspaper_b3.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_tag.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_tag.html';
$i++;
$modversion['templates'][$i]['file']        = 'tadnews_adm_tag_b3.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_tag_b3.html';

//---評論設定---//
$modversion['hasComments']          = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'nsn';

//---搜尋設定---//
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "tadnews_search";

//---區塊設定---//
$i                                       = 1;
$modversion['blocks'][$i]['file']        = "tadnews_cate.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC1;
$modversion['blocks'][$i]['show_func']   = "tadnews_cate_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_cate.html";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_content_block.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC2;
$modversion['blocks'][$i]['show_func']   = "tadnews_content_block_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_content_block.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_content_block_edit";
$modversion['blocks'][$i]['options']     = "5|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;|0|1|width:80px;height:60px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0|";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_re_block.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME3;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC3;
$modversion['blocks'][$i]['show_func']   = "tadnews_b_show_3";
$modversion['blocks'][$i]['template']    = "tadnews_block_re_block.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_re_edit";
$modversion['blocks'][$i]['options']     = "10|160";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_newspaper.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME4;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC4;
$modversion['blocks'][$i]['show_func']   = "tadnews_newspaper";
$modversion['blocks'][$i]['template']    = "tadnews_block_newspaper.html";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_newspaper_list.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME5;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC5;
$modversion['blocks'][$i]['show_func']   = "tadnews_newspaper_list";
$modversion['blocks'][$i]['template']    = "tadnews_block_newspaper_list.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_newspaper_list_edit";
$modversion['blocks'][$i]['options']     = "10";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_cate_news.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME6;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC6;
$modversion['blocks'][$i]['show_func']   = "tadnews_cate_news";
$modversion['blocks'][$i]['template']    = "tadnews_block_cate_news.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_cate_news_edit";
$modversion['blocks'][$i]['options']     = "|10|1|0|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_page.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME7;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC7;
$modversion['blocks'][$i]['show_func']   = "tadnews_page";
$modversion['blocks'][$i]['template']    = "tadnews_block_page.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_page_edit";
$modversion['blocks'][$i]['options']     = "|160|12px";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_focus_news.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME8;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC8;
$modversion['blocks'][$i]['show_func']   = "tadnews_focus_news";
$modversion['blocks'][$i]['template']    = "tadnews_block_focus_news.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_focus_news_edit";
$modversion['blocks'][$i]['options']     = "|full";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_my_page.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME9;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC9;
$modversion['blocks'][$i]['show_func']   = "tadnews_my_page";
$modversion['blocks'][$i]['template']    = "tadnews_block_my_page.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_my_page_edit";
$modversion['blocks'][$i]['options']     = "";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_list_content_block.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME10;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC10;
$modversion['blocks'][$i]['show_func']   = "tadnews_list_content_block_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_list_content_block.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_list_content_block_edit";
$modversion['blocks'][$i]['options']     = "5|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;|0|1|width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0|list|0";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_table_content_block.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME11;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC11;
$modversion['blocks'][$i]['show_func']   = "tadnews_table_content_block_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_table_content_block.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_table_content_block_edit";
$modversion['blocks'][$i]['options']     = "6|1|start_day|news_title|uid|ncsn|counter|0";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_slidernews.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME13;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC13;
$modversion['blocks'][$i]['show_func']   = "tadnews_slidernews_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_slidernews.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_slidernews_edit";
$modversion['blocks'][$i]['options']     = "670|250|5|90|";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_slidernews2.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_BNAME14;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_BDESC14;
$modversion['blocks'][$i]['show_func']   = "tadnews_slidernews2_show";
$modversion['blocks'][$i]['template']    = "tadnews_block_slidernews2.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_slidernews2_edit";
$modversion['blocks'][$i]['options']     = "5|90|ResponsiveSlides|";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_marquee.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_MARQUEE;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_MARQUEE_DESC;
$modversion['blocks'][$i]['show_func']   = "tadnews_marquee";
$modversion['blocks'][$i]['template']    = "tadnews_block_marquee.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_marquee_edit";
$modversion['blocks'][$i]['options']     = "5|0|down|5000||";

$i++;
$modversion['blocks'][$i]['file']        = "tadnews_covered.php";
$modversion['blocks'][$i]['name']        = _MI_TADNEWS_COVERED;
$modversion['blocks'][$i]['description'] = _MI_TADNEWS_COVERED_DESC;
$modversion['blocks'][$i]['show_func']   = "tadnews_covered";
$modversion['blocks'][$i]['template']    = "tadnews_block_covered.html";
$modversion['blocks'][$i]['edit_func']   = "tadnews_covered_edit";
$modversion['blocks'][$i]['options']     = "3|2|66|font-size:13px ;color: gray; line-height: 1.5; font-family:新細明體;|";

//---偏好設定---//
$i                                       = 1;
$modversion['config'][$i]['name']        = 'show_num';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_TITLE1';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_DESC1';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;

$i++;
$modversion['config'][$i]['name']        = 'show_mode';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_SHOW_MODE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = "summary";
$modversion['config'][$i]['options']     = array('_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list', '_MI_TADNEWS_SHOW_MODE_OPT3' => 'cate');

$i++;
$modversion['config'][$i]['name']        = 'cate_show_mode';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_CATE_SHOW_MODE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CATE_SHOW_MODE_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = "summary";
$modversion['config'][$i]['options']     = array('_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list');

$i++;
$modversion['config'][$i]['name']        = 'show_bbcode';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_SHOW_BB';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_BB_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

$i++;
$modversion['config'][$i]['name']        = 'cate_pic_width';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_CATE_PIC_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CATE_PIC_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 100;

$i++;
$modversion['config'][$i]['name']        = 'pic_width';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_PIC_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_PIC_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 550;

$i++;
$modversion['config'][$i]['name']        = 'thumb_width';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_THUMB_WIDTH';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_THUMB_WIDTH_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 120;

$i++;
$modversion['config'][$i]['name']        = 'use_newspaper';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_USE_NEWSPAPER';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_USE_NEWSPAPER_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

$i++;
$modversion['config'][$i]['name']        = 'use_archive';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_USE_USE_ARCHIVE';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_USE_USE_ARCHIVE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

$i++;
$modversion['config'][$i]['name']        = 'show_submenu';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_SHOW_SUBMENU';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SHOW_SUBMENU_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

$i++;
$modversion['config'][$i]['name']        = 'download_after_read';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_DOWNLOAD_AFTER_READ';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_DOWNLOAD_AFTER_READ_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

$i++;
$modversion['config'][$i]['name']        = 'creat_cate_group';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_CREAT_CATE_GROUP';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_CREAT_CATE_GROUP_DESC';
$modversion['config'][$i]['formtype']    = 'group_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = array(1);

$i++;
$modversion['config'][$i]['name']        = 'summary_lengths';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_SUMMARY_LENGTHS';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_SUMMARY_LENGTHS_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 100;

$i++;
$modversion['config'][$i]['name']        = 'facebook_comments_width';
$modversion['config'][$i]['title']       = '_MI_FBCOMMENT_TITLE';
$modversion['config'][$i]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'use_pda';
$modversion['config'][$i]['title']       = '_MI_USE_PDA_TITLE';
$modversion['config'][$i]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '0';

$i++;
$modversion['config'][$i]['name']        = 'use_social_tools';
$modversion['config'][$i]['title']       = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][$i]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'use_star_rating';
$modversion['config'][$i]['title']       = '_MI_STAR_RATING_TITLE';
$modversion['config'][$i]['description'] = '_MI_STAR_RATING_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '1';

$i++;
$modversion['config'][$i]['name']        = 'cover_pic_css';
$modversion['config'][$i]['title']       = '_MI_COVER_PIC_CSS';
$modversion['config'][$i]['description'] = '_MI_COVER_PIC_CSS_DESC';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'width:200px; height:150px; border:1px solid #909090; background-position:left top; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;';

$i++;
$modversion['config'][$i]['name']        = 'editor';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_EDITOR';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_EDITOR_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = "ckeditor";
$modversion['config'][$i]['options']     = array('CKEditor' => 'ckeditor', 'elRTE' => 'elrte');

$i++;
$modversion['config'][$i]['name']        = 'fancybox_playspeed';
$modversion['config'][$i]['title']       = '_MI_TADNEWS_FANCYBOX_SPEED';
$modversion['config'][$i]['description'] = '_MI_TADNEWS_FANCYBOX_SPEED_DESC';
$modversion['config'][$i]['formtype']    = 'text';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = "5000";
