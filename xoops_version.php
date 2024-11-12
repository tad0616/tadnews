<?php
//---基本設定---//
$modversion = [];
global $xoopsConfig;
$language = isset($xoopsConfig['language']) ? $xoopsConfig['language'] : 'tchinese_utf8';
//---模組基本資訊---//
$modversion['name'] = _MI_TADNEWS_NAME;
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '5.0.0-Stable' : '5.0';
$modversion['description'] = _MI_TADNEWS_DESC;
$modversion['author'] = 'Tad (tad0616@gmail.com)';
$modversion['credits'] = 'geek01';
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$language}.png";
$modversion['dirname'] = basename(__DIR__);

//---模組狀態資訊---//
$modversion['release_date'] = '2024-11-18';
$modversion['module_website_url'] = 'https://tad0616.net/';
$modversion['module_website_name'] = _MI_TADNEWS_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'https://tad0616.net/';
$modversion['author_website_name'] = 'Tad';
$modversion['min_php'] = '5.4';
$modversion['min_xoops'] = '2.5.10';
$modversion['min_db'] = ['mysql' => '5.0.7', 'mysqli' => '5.0.7'];

//---paypal資訊---//
$modversion['paypal'] = [
    'business' => 'tad0616@gmail.com',
    'item_name' => 'Donation : ' . _MI_TAD_WEB,
    'amount' => 0,
    'currency_code' => 'USD',
];

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.{$language}.sql";
$modversion['tables'] = [
    'tad_news',
    'tad_news_cate',
    'tadnews_files_center',
    'tad_news_paper',
    'tad_news_paper_setup',
    'tad_news_paper_email',
    'tad_news_sign',
    'tad_news_paper_send_log',
    'tad_news_tags',
    'tadnews_rank',
    'tadnews_data_center',
];

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
            $modversion['sub'][] = ['name' => _MI_TADNEWS_ADMENU2, 'url' => 'post.php'];
        }
        if ($xoopsModuleConfig['use_archive'] == '1') {
            $modversion['sub'][] = ['name' => _MI_TADNEWS_ARCHIVE, 'url' => 'archive.php'];
        }
        if ($xoopsModuleConfig['use_newspaper'] == '1') {
            $modversion['sub'][] = ['name' => _MI_TADNEWS_NEWSPAPER, 'url' => 'newspaper.php'];
        }
    }
}

$modversion['onInstall'] = 'include/onInstall.php';
$modversion['onUpdate'] = 'include/onUpdate.php';
$modversion['onUninstall'] = 'include/onUninstall.php';
$modversion['templates'] = [
    ['file' => 'tadnews_index.tpl', 'description' => 'tadnews_index.tpl'],
    ['file' => 'tadnews_rss.tpl', 'description' => 'tadnews_rss.tpl'],
    ['file' => 'tadnews_post.tpl', 'description' => 'tadnews_post.tpl'],
    ['file' => 'tadnews_page.tpl', 'description' => 'tadnews_page.tpl'],
    ['file' => 'tadnews_newspaper.tpl', 'description' => 'tadnews_newspaper.tpl'],
    ['file' => 'tadnews_my_news.tpl', 'description' => 'tadnews_my_news.tpl'],
    ['file' => 'tadnews_adm_main.tpl', 'description' => 'tadnews_adm_main.tpl'],
    ['file' => 'tadnews_adm_page.tpl', 'description' => 'tadnews_adm_page.tpl'],
    ['file' => 'tadnews_adm_newspaper.tpl', 'description' => 'tadnews_adm_newspaper.tpl'],
    ['file' => 'tadnews_adm_tag.tpl', 'description' => 'tadnews_adm_tag.tpl'],
    ['file' => 'tadnews_adm_import.tpl', 'description' => 'tadnews_adm_import.tpl'],
];

//---評論設定---//
$modversion['hasComments'] = 0;

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.php';
$modversion['search']['func'] = 'tadnews_search';
$modversion['blocks'] = [
    [
        'file' => 'tadnews_cate.php',
        'name' => _MI_TADNEWS_BNAME1,
        'description' => _MI_TADNEWS_BDESC1,
        'show_func' => 'tadnews_cate_show',
        'template' => 'tadnews_block_cate.tpl',
    ],
    [
        'file' => 'tadnews_content_block.php',
        'name' => _MI_TADNEWS_BNAME2,
        'description' => _MI_TADNEWS_BDESC2,
        'show_func' => 'tadnews_content_block_show',
        'template' => 'tadnews_block_content_block.tpl',
        'edit_func' => 'tadnews_content_block_edit',
        'options' => '5|100|color: #707070; font-size: 0.95rem; margin-top:3px; line-height:150%; white-space: pre-line;|0|1|width:80px;height:60px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0|',
    ],
    [
        'file' => 'tadnews_newspaper.php',
        'name' => _MI_TADNEWS_BNAME4,
        'description' => _MI_TADNEWS_BDESC4,
        'show_func' => 'tadnews_newspaper',
        'template' => 'tadnews_block_newspaper.tpl',
    ],
    [
        'file' => 'tadnews_newspaper_list.php',
        'name' => _MI_TADNEWS_BNAME5,
        'description' => _MI_TADNEWS_BDESC5,
        'show_func' => 'tadnews_newspaper_list',
        'template' => 'tadnews_block_newspaper_list.tpl',
        'edit_func' => 'tadnews_newspaper_list_edit',
        'options' => '10',
    ],
    [
        'file' => 'tadnews_cate_news.php',
        'name' => _MI_TADNEWS_BNAME6,
        'description' => _MI_TADNEWS_BDESC6,
        'show_func' => 'tadnews_cate_news',
        'template' => 'tadnews_block_cate_news.tpl',
        'edit_func' => 'tadnews_cate_news_edit',
        'options' => '|10|1|0|100|color:#707070; font-size: 0.75rem; margin-top:3px; line-height:180%;',
    ],
    [
        'file' => 'tadnews_page.php',
        'name' => _MI_TADNEWS_BNAME7,
        'description' => _MI_TADNEWS_BDESC7,
        'show_func' => 'tadnews_page',
        'template' => 'tadnews_block_page.tpl',
        'edit_func' => 'tadnews_page_edit',
        'options' => '|160|1em',
    ],
    [
        'file' => 'tadnews_focus_news.php',
        'name' => _MI_TADNEWS_BNAME8,
        'description' => _MI_TADNEWS_BDESC8,
        'show_func' => 'tadnews_focus_news',
        'template' => 'tadnews_block_focus_news.tpl',
        'edit_func' => 'tadnews_focus_news_edit',
        'options' => '|full',
    ],
    [
        'file' => 'tadnews_my_page.php',
        'name' => _MI_TADNEWS_BNAME9,
        'description' => _MI_TADNEWS_BDESC9,
        'show_func' => 'tadnews_my_page',
        'template' => 'tadnews_block_my_page.tpl',
        'edit_func' => 'tadnews_my_page_edit',
        'options' => '',
    ],
    [
        'file' => 'tadnews_list_content_block.php',
        'name' => _MI_TADNEWS_BNAME10,
        'description' => _MI_TADNEWS_BDESC10,
        'show_func' => 'tadnews_list_content_block_show',
        'template' => 'tadnews_block_list_content_block.tpl',
        'edit_func' => 'tadnews_list_content_block_edit',
        'options' => '5|100|color:gray;font-size: 0.8rem; margin-top:3px;line-height:150%;|0|1|width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0||list|0|0',
    ],
    [
        'file' => 'tadnews_table_content_block.php',
        'name' => _MI_TADNEWS_BNAME11,
        'description' => _MI_TADNEWS_BDESC11,
        'show_func' => 'tadnews_table_content_block_show',
        'template' => 'tadnews_block_table_content_block.tpl',
        'edit_func' => 'tadnews_table_content_block_edit',
        'options' => '6|1|start_day|news_title|uid|ncsn|counter|0||0',
    ],
    [
        'file' => 'tadnews_slidernews.php',
        'name' => _MI_TADNEWS_BNAME13,
        'description' => _MI_TADNEWS_BDESC13,
        'show_func' => 'tadnews_slidernews_show',
        'template' => 'tadnews_block_slidernews.tpl',
        'edit_func' => 'tadnews_slidernews_edit',
        'options' => '670|250|5|90|',
    ],
    [
        'file' => 'tadnews_slidernews2.php',
        'name' => _MI_TADNEWS_BNAME14,
        'description' => _MI_TADNEWS_BDESC14,
        'show_func' => 'tadnews_slidernews2_show',
        'template' => 'tadnews_block_slidernews2.tpl',
        'edit_func' => 'tadnews_slidernews2_edit',
        'options' => '5|90|ResponsiveSlides|',
    ],
    [
        'file' => 'tadnews_marquee.php',
        'name' => _MI_TADNEWS_MARQUEE,
        'description' => _MI_TADNEWS_MARQUEE_DESC,
        'show_func' => 'tadnews_marquee',
        'template' => 'tadnews_block_marquee.tpl',
        'edit_func' => 'tadnews_marquee_edit',
        'options' => '5|0|down|5000||',
    ],
    [
        'file' => 'tadnews_covered.php',
        'name' => _MI_TADNEWS_COVERED,
        'description' => _MI_TADNEWS_COVERED_DESC,
        'show_func' => 'tadnews_covered',
        'template' => 'tadnews_block_covered.tpl',
        'edit_func' => 'tadnews_covered_edit',
        'options' => '3|2|66|font-size: 0.8rem; color: #707070; line-height: 180%;||1|1|80px',
    ],
    [
        'file' => 'tadnews_page_list.php',
        'name' => _MI_TADNEWS_PAGE_LIST,
        'description' => _MI_TADNEWS_PAGE_LIST_DESC,
        'show_func' => 'tadnews_page_list',
        'template' => 'tadnews_block_page_list.tpl',
        'edit_func' => 'tadnews_page_list_edit',
        'options' => '|#9ea200|1|1|#ffffff|padding: 4px; border-radius: 5px;|font-size: 1.1rem; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;',
    ],
    [
        'file' => 'tadnews_tab_news.php',
        'name' => _MI_TADNEWS_TAB_NEWS,
        'description' => _MI_TADNEWS_TAB_NEWS_DESC,
        'show_func' => 'tadnews_tab_news',
        'template' => 'tadnews_block_tab_news.tpl',
        'edit_func' => 'tadnews_tab_news_edit',
        'options' => '|10|default|#FFFFFF|#E0D9D9|#9C905C|#9C905C|0|16|0',
    ],
    [
        'file' => 'tadnews_tag_news.php',
        'name' => _MI_TADNEWS_TAG_NEWS,
        'description' => _MI_TADNEWS_TAG_NEWS_DESC,
        'show_func' => 'tadnews_tag_news',
        'template' => 'tadnews_block_tag_news.tpl',
        'edit_func' => 'tadnews_tag_news_edit',
        'options' => '|10|default|#FFFFFF|#E0D9D9|#9C905C|#9C905C|0|16|0',
    ],
    [
        'file' => 'tadnews_page_menu.php',
        'name' => _MI_TADNEWS_PAGE_MENU . '[hide]',
        'description' => _MI_TADNEWS_PAGE_MENU_DESC,
        'show_func' => 'tadnews_page_menu',
        'template' => 'tadnews_block_page_menu.tpl',
        'edit_func' => 'tadnews_page_menu_edit',
        'options' => '1|1|#dbff40|#5e6b00|padding: 4px; border-radius: 5px;|font-size: 1.1rem; text-shadow: 0px 1px #0d4e5c, 1px 0px #0d4e5c, -1px 0px #0d4e5c, 0px -1px #0d4e5c, -1px -1px #0d4e5c, 1px 1px #0d4e5c, 1px -1px #0d4e5c, -1px 1px #0d4e5c;',
    ],
];

$modversion['config'] = [
    ['name' => 'show_num', 'title' => '_MI_TADNEWS_TITLE1', 'description' => '_MI_TADNEWS_DESC1', 'formtype' => 'textbox', 'valuetype' => 'int', 'default' => 10],
    ['name' => 'show_mode', 'title' => '_MI_TADNEWS_SHOW_MODE', 'description' => '_MI_TADNEWS_SHOW_MODE_DESC', 'formtype' => 'select', 'valuetype' => 'text', 'default' => 'summary', 'options' => ['_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list', '_MI_TADNEWS_SHOW_MODE_OPT3' => 'cate']],
    ['name' => 'cate_show_mode', 'title' => '_MI_TADNEWS_CATE_SHOW_MODE', 'description' => '_MI_TADNEWS_CATE_SHOW_MODE_DESC', 'formtype' => 'select', 'valuetype' => 'text', 'default' => 'summary', 'options' => ['_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary', '_MI_TADNEWS_SHOW_MODE_OPT2' => 'list']],
    ['name' => 'show_bbcode', 'title' => '_MI_TADNEWS_SHOW_BB', 'description' => '_MI_TADNEWS_SHOW_BB_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
    ['name' => 'cate_pic_width', 'title' => '_MI_TADNEWS_CATE_PIC_WIDTH', 'description' => '_MI_TADNEWS_CATE_PIC_WIDTH_DESC', 'formtype' => 'text', 'valuetype' => 'int', 'default' => 480],
    ['name' => 'pic_width', 'title' => '_MI_TADNEWS_PIC_WIDTH', 'description' => '_MI_TADNEWS_PIC_WIDTH_DESC', 'formtype' => 'text', 'valuetype' => 'int', 'default' => 1920],
    ['name' => 'thumb_width', 'title' => '_MI_TADNEWS_THUMB_WIDTH', 'description' => '_MI_TADNEWS_THUMB_WIDTH_DESC', 'formtype' => 'text', 'valuetype' => 'int', 'default' => 480],
    ['name' => 'use_newspaper', 'title' => '_MI_TADNEWS_USE_NEWSPAPER', 'description' => '_MI_TADNEWS_USE_NEWSPAPER_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1],
    ['name' => 'use_archive', 'title' => '_MI_TADNEWS_USE_USE_ARCHIVE', 'description' => '_MI_TADNEWS_USE_USE_ARCHIVE_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1],
    ['name' => 'show_submenu', 'title' => '_MI_TADNEWS_SHOW_SUBMENU', 'description' => '_MI_TADNEWS_SHOW_SUBMENU_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1],
    ['name' => 'download_after_read', 'title' => '_MI_TADNEWS_DOWNLOAD_AFTER_READ', 'description' => '_MI_TADNEWS_DOWNLOAD_AFTER_READ_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
    ['name' => 'creat_cate_group', 'title' => '_MI_TADNEWS_CREAT_CATE_GROUP', 'description' => '_MI_TADNEWS_CREAT_CATE_GROUP_DESC', 'formtype' => 'group_multi', 'valuetype' => 'array', 'default' => [1]],
    ['name' => 'use_top_group', 'title' => '_MI_TADNEWS_USE_TOP_GROUP', 'description' => '_MI_TADNEWS_USE_TOP_GROUP_DESC', 'formtype' => 'group_multi', 'valuetype' => 'array', 'default' => []],
    ['name' => 'top_max_day', 'title' => '_MI_TADNEWS_TOP_MAX_DAY', 'description' => '_MI_TADNEWS_TOP_MAX_DAY_DESC', 'formtype' => 'text', 'valuetype' => 'int', 'default' => 14],
    ['name' => 'summary_lengths', 'title' => '_MI_TADNEWS_SUMMARY_LENGTHS', 'description' => '_MI_TADNEWS_SUMMARY_LENGTHS_DESC', 'formtype' => 'text', 'valuetype' => 'int', 'default' => 100],
    ['name' => 'use_social_tools', 'title' => '_MI_SOCIALTOOLS_TITLE', 'description' => '_MI_SOCIALTOOLS_TITLE_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1],
    ['name' => 'use_star_rating', 'title' => '_MI_STAR_RATING_TITLE', 'description' => '_MI_STAR_RATING_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
    ['name' => 'cover_pic_css', 'title' => '_MI_COVER_PIC_CSS', 'description' => '_MI_COVER_PIC_CSS_DESC', 'formtype' => 'textarea', 'valuetype' => 'text', 'default' => 'width:200px; height:150px; border:1px solid #909090; background-position:left top; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;'],
    ['name' => 'upload_deny', 'title' => '_MI_TADNEWS_UPLOAD_DENY', 'description' => '_MI_TADNEWS_UPLOAD_DENY_DESC', 'formtype' => 'text', 'valuetype' => 'text', 'default' => ''],
    ['name' => 'show_next_btn', 'title' => '_MI_SHOW_NEXT_BTN', 'description' => '_MI_SHOW_NEXT_BTN_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
    ['name' => 'show_rss', 'title' => '_MI_SHOW_RSS', 'description' => '_MI_SHOW_RSS_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
    ['name' => 'use_table_shadow', 'title' => '_MI_USE_TABLE_SHADOW', 'description' => '_MI_USE_TABLE_SHADOW_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 1],
    ['name' => 'uid_chk', 'title' => '_MI_UPDATE_UID_CHK', 'description' => '_MI_UPDATE_UID_CHK_DESC', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0],
];
