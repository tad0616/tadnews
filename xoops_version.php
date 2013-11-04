<?php
//---基本設定---//
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_TADNEWS_NAME;
$modversion['version'] = 3.0;
$modversion['description'] = _MI_TADNEWS_DESC;
$modversion['author'] = 'Tad (tad0616@gmail.com)';
$modversion['credits'] = 'geek01';
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html/';
$modversion['image'] = "images/logo_{$xoopsConfig['language']}.png";
$modversion['dirname'] = basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['release_date'] = '2013/10/16';
$modversion['module_website_url'] = 'http://tad0616.net/';
$modversion['module_website_name'] = _MI_TADNEWS_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = 'http://tad0616.net/';
$modversion['author_website_name'] = 'Tad';
$modversion['min_php']='5.2';
$modversion['min_xoops']='2.5';
$modversion['min_db'] = array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tad0616@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation : ' . _MI_TADNEWS_DESC;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';


//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.{$xoopsConfig['language']}.sql";
$modversion['tables'][1] = "tad_news";
$modversion['tables'][2] = "tad_news_cate";
$modversion['tables'][3] = "tadnews_files_center";
$modversion['tables'][4] = "tad_news_paper";
$modversion['tables'][5] = "tad_news_paper_setup";
$modversion['tables'][6] = "tad_news_paper_email";
$modversion['tables'][7] = "tad_news_sign";
$modversion['tables'][8] = "tad_news_paper_send_log";
$modversion['tables'][9] = "tad_news_tags";
$modversion['tables'][10] = "tadnews_rank";



//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;

global $xoopsModuleConfig , $xoopsUser;
if(isset($xoopsModuleConfig['show_submenu'])){
  if($xoopsModuleConfig['show_submenu']=='1'){
    if($xoopsUser){
      $modversion['sub'][1]['name'] = _MI_TADNEWS_ADMENU2;
      $modversion['sub'][1]['url'] = "post.php";
    }
    if($xoopsModuleConfig['use_archive']=='1'){
      $modversion['sub'][2]['name'] = _MI_TADNEWS_ARCHIVE;
      $modversion['sub'][2]['url'] = "archive.php";
    }
    if($xoopsModuleConfig['use_newspaper']=='1'){
      $modversion['sub'][3]['name'] = _MI_TADNEWS_NEWSPAPER;
      $modversion['sub'][3]['url'] = "newspaper.php";
    }
  }
}

$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";


//---樣板設定---//
$i=0;
$modversion['templates'][$i]['file'] = 'tadnews_list_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_list_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_news_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_news_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_rss.html';
$modversion['templates'][$i]['description'] = 'tadnews_rss.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_post_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_post_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_archive_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_archive_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_page_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_page_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_index_summary_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_summary_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_index_cate_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_index_cate_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_newspaper_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_newspaper_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_list_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_list_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_page_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_page_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_cate_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_cate_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_newspaper_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_newspaper_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_tag_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_tag_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_adm_page_cate_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_adm_page_cate_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_page_list_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_page_list_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_sign_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_sign_tpl.html';
$i++;
$modversion['templates'][$i]['file'] = 'tadnews_my_news_tpl.html';
$modversion['templates'][$i]['description'] = 'tadnews_my_news_tpl.html';



//---評論設定---//
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'nsn';

//---搜尋設定---//
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.php";
$modversion['search']['func'] = "tadnews_search";

//---區塊設定---//
$modversion['blocks'][1]['file'] = "tadnews_cate.php";
$modversion['blocks'][1]['name'] = _MI_TADNEWS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_TADNEWS_BDESC1;
$modversion['blocks'][1]['show_func'] = "tadnews_cate_show";
$modversion['blocks'][1]['template'] = "tadnews_cate.html";

$modversion['blocks'][2]['file'] = "tadnews_content_block.php";
$modversion['blocks'][2]['name'] = _MI_TADNEWS_BNAME2;
$modversion['blocks'][2]['description'] = _MI_TADNEWS_BDESC2;
$modversion['blocks'][2]['show_func'] = "tadnews_content_block_show";
$modversion['blocks'][2]['template'] = "tadnews_content_block.html";
$modversion['blocks'][2]['edit_func'] = "tadnews_content_block_edit";
$modversion['blocks'][2]['options'] = "5|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;|0|1|width:80px;height:60px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0|";

$modversion['blocks'][3]['file'] = "tadnews_re_block.php";
$modversion['blocks'][3]['name'] = _MI_TADNEWS_BNAME3;
$modversion['blocks'][3]['description'] = _MI_TADNEWS_BDESC3;
$modversion['blocks'][3]['show_func'] = "tadnews_b_show_3";
$modversion['blocks'][3]['template'] = "tadnews_re_block.html";
$modversion['blocks'][3]['edit_func'] = "tadnews_re_edit";
$modversion['blocks'][3]['options'] = "10|160";


$modversion['blocks'][4]['file'] = "tadnews_newspaper.php";
$modversion['blocks'][4]['name'] = _MI_TADNEWS_BNAME4;
$modversion['blocks'][4]['description'] = _MI_TADNEWS_BDESC4;
$modversion['blocks'][4]['show_func'] = "tadnews_newspaper";
$modversion['blocks'][4]['template'] = "tadnews_newspaper.html";

$modversion['blocks'][5]['file'] = "tadnews_newspaper_list.php";
$modversion['blocks'][5]['name'] = _MI_TADNEWS_BNAME5;
$modversion['blocks'][5]['description'] = _MI_TADNEWS_BDESC5;
$modversion['blocks'][5]['show_func'] = "tadnews_newspaper_list";
$modversion['blocks'][5]['template'] = "tadnews_newspaper_list.html";
$modversion['blocks'][5]['edit_func'] = "tadnews_newspaper_list_edit";
$modversion['blocks'][5]['options'] = "10";

$modversion['blocks'][6]['file'] = "tadnews_cate_news.php";
$modversion['blocks'][6]['name'] = _MI_TADNEWS_BNAME6;
$modversion['blocks'][6]['description'] = _MI_TADNEWS_BDESC6;
$modversion['blocks'][6]['show_func'] = "tadnews_cate_news";
$modversion['blocks'][6]['template'] = "tadnews_cate_news.html";
$modversion['blocks'][6]['edit_func'] = "tadnews_cate_news_edit";
$modversion['blocks'][6]['options'] = "|10|1|0|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;";

$modversion['blocks'][7]['file'] = "tadnews_page.php";
$modversion['blocks'][7]['name'] = _MI_TADNEWS_BNAME7;
$modversion['blocks'][7]['description'] = _MI_TADNEWS_BDESC7;
$modversion['blocks'][7]['show_func'] = "tadnews_page";
$modversion['blocks'][7]['template'] = "tadnews_page.html";
$modversion['blocks'][7]['edit_func'] = "tadnews_page_edit";
$modversion['blocks'][7]['options'] = "|160";

$modversion['blocks'][8]['file'] = "tadnews_focus_news.php";
$modversion['blocks'][8]['name'] = _MI_TADNEWS_BNAME8;
$modversion['blocks'][8]['description'] = _MI_TADNEWS_BDESC8;
$modversion['blocks'][8]['show_func'] = "tadnews_focus_news";
$modversion['blocks'][8]['template'] = "tadnews_focus_news.html";
$modversion['blocks'][8]['edit_func'] = "tadnews_focus_news_edit";
$modversion['blocks'][8]['options'] = "|full";

$modversion['blocks'][9]['file'] = "tadnews_my_page.php";
$modversion['blocks'][9]['name'] = _MI_TADNEWS_BNAME9;
$modversion['blocks'][9]['description'] = _MI_TADNEWS_BDESC9;
$modversion['blocks'][9]['show_func'] = "tadnews_my_page";
$modversion['blocks'][9]['template'] = "tadnews_my_page.html";
$modversion['blocks'][9]['edit_func'] = "tadnews_my_page_edit";
$modversion['blocks'][9]['options'] = "";

$modversion['blocks'][10]['file'] = "tadnews_list_content_block.php";
$modversion['blocks'][10]['name'] = _MI_TADNEWS_BNAME10;
$modversion['blocks'][10]['description'] = _MI_TADNEWS_BDESC10;
$modversion['blocks'][10]['show_func'] = "tadnews_list_content_block_show";
$modversion['blocks'][10]['template'] = "tadnews_list_content_block.html";
$modversion['blocks'][10]['edit_func'] = "tadnews_list_content_block_edit";
$modversion['blocks'][10]['options'] = "5|100|color:gray;font-size:11px;margin-top:3px;line-height:150%;|0|1|width:60px;height:30px;float:left;border:0px solid #9999CC;margin:0px 4px 4px 0px;overflow:hidden;background-size:cover;|0";


$modversion['blocks'][11]['file'] = "tadnews_table_content_block.php";
$modversion['blocks'][11]['name'] = _MI_TADNEWS_BNAME11;
$modversion['blocks'][11]['description'] = _MI_TADNEWS_BDESC11;
$modversion['blocks'][11]['show_func'] = "tadnews_table_content_block_show";
$modversion['blocks'][11]['template'] = "tadnews_table_content_block.html";
$modversion['blocks'][11]['edit_func'] = "tadnews_table_content_block_edit";
$modversion['blocks'][11]['options'] = "6|1|start_day|news_title|uid|ncsn|counter|0";


$modversion['blocks'][12]['file'] = "tadnews_qrcode.php";
$modversion['blocks'][12]['name'] = _MI_TADNEWS_BNAME12;
$modversion['blocks'][12]['description'] = _MI_TADNEWS_BDESC12;
$modversion['blocks'][12]['show_func'] = "tadnews_qrcode_show";
$modversion['blocks'][12]['template'] = "tadnews_qrcode.html";


$modversion['blocks'][13]['file'] = "tadnews_slidernews.php";
$modversion['blocks'][13]['name'] = _MI_TADNEWS_BNAME13;
$modversion['blocks'][13]['description'] = _MI_TADNEWS_BDESC13;
$modversion['blocks'][13]['show_func'] = "tadnews_slidernews_show";
$modversion['blocks'][13]['template'] = "tadnews_slidernews.html";
$modversion['blocks'][13]['edit_func'] = "tadnews_slidernews_edit";
$modversion['blocks'][13]['options'] = "670|250|5|90|";

$modversion['blocks'][14]['file'] = "tadnews_slidernews2.php";
$modversion['blocks'][14]['name'] = _MI_TADNEWS_BNAME14;
$modversion['blocks'][14]['description'] = _MI_TADNEWS_BDESC14;
$modversion['blocks'][14]['show_func'] = "tadnews_slidernews2_show";
$modversion['blocks'][14]['template'] = "tadnews_slidernews2.html";
$modversion['blocks'][14]['edit_func'] = "tadnews_slidernews2_edit";
$modversion['blocks'][14]['options'] = "5|90|ResponsiveSlides|";

//---偏好設定---//
$modversion['config'][1]['name'] = 'show_num';
$modversion['config'][1]['title'] = '_MI_TADNEWS_TITLE1';
$modversion['config'][1]['description'] = '_MI_TADNEWS_DESC1';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 10;

$modversion['config'][4]['name'] = 'show_mode';
$modversion['config'][4]['title'] = '_MI_TADNEWS_SHOW_MODE';
$modversion['config'][4]['description'] = '_MI_TADNEWS_SHOW_MODE_DESC';
$modversion['config'][4]['formtype'] = 'select';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = "summary";
$modversion['config'][4]['options']	= array('_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary','_MI_TADNEWS_SHOW_MODE_OPT2' => 'list','_MI_TADNEWS_SHOW_MODE_OPT3' => 'cate');

$modversion['config'][8]['name'] = 'cate_show_mode';
$modversion['config'][8]['title'] = '_MI_TADNEWS_CATE_SHOW_MODE';
$modversion['config'][8]['description'] = '_MI_TADNEWS_CATE_SHOW_MODE_DESC';
$modversion['config'][8]['formtype'] = 'select';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = "summary";
$modversion['config'][8]['options']	= array('_MI_TADNEWS_SHOW_MODE_OPT1' => 'summary','_MI_TADNEWS_SHOW_MODE_OPT2' => 'list');


$modversion['config'][6]['name'] = 'show_bbcode';
$modversion['config'][6]['title'] = '_MI_TADNEWS_SHOW_BB';
$modversion['config'][6]['description'] = '_MI_TADNEWS_SHOW_BB_DESC';
$modversion['config'][6]['formtype'] = 'yesno';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 0;

$modversion['config'][7]['name'] = 'cate_pic_width';
$modversion['config'][7]['title'] = '_MI_TADNEWS_CATE_PIC_WIDTH';
$modversion['config'][7]['description'] = '_MI_TADNEWS_CATE_PIC_WIDTH_DESC';
$modversion['config'][7]['formtype'] = 'text';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 100;


$modversion['config'][9]['name'] = 'pic_width';
$modversion['config'][9]['title'] = '_MI_TADNEWS_PIC_WIDTH';
$modversion['config'][9]['description'] = '_MI_TADNEWS_PIC_WIDTH_DESC';
$modversion['config'][9]['formtype'] = 'text';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = 550;


$modversion['config'][10]['name'] = 'thumb_width';
$modversion['config'][10]['title'] = '_MI_TADNEWS_THUMB_WIDTH';
$modversion['config'][10]['description'] = '_MI_TADNEWS_THUMB_WIDTH_DESC';
$modversion['config'][10]['formtype'] = 'text';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = 120;


$modversion['config'][11]['name'] = 'use_newspaper';
$modversion['config'][11]['title'] = '_MI_TADNEWS_USE_NEWSPAPER';
$modversion['config'][11]['description'] = '_MI_TADNEWS_USE_NEWSPAPER_DESC';
$modversion['config'][11]['formtype'] = 'yesno';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = 1;

$modversion['config'][12]['name'] = 'use_archive';
$modversion['config'][12]['title'] = '_MI_TADNEWS_USE_USE_ARCHIVE';
$modversion['config'][12]['description'] = '_MI_TADNEWS_USE_USE_ARCHIVE_DESC';
$modversion['config'][12]['formtype'] = 'yesno';
$modversion['config'][12]['valuetype'] = 'int';
$modversion['config'][12]['default'] = 1;
/*
$modversion['config'][22]['name'] = 'use_embed';
$modversion['config'][22]['title'] = '_MI_TADNEWS_USE_EMBED';
$modversion['config'][22]['description'] = '_MI_TADNEWS_USE_EMBED_DESC';
$modversion['config'][22]['formtype'] = 'yesno';
$modversion['config'][22]['valuetype'] = 'int';
$modversion['config'][22]['default'] = 1;
*/

$modversion['config'][13]['name'] = 'show_submenu';
$modversion['config'][13]['title'] = '_MI_TADNEWS_SHOW_SUBMENU';
$modversion['config'][13]['description'] = '_MI_TADNEWS_SHOW_SUBMENU_DESC';
$modversion['config'][13]['formtype'] = 'yesno';
$modversion['config'][13]['valuetype'] = 'int';
$modversion['config'][13]['default'] = 1;


$modversion['config'][15]['name'] = 'download_after_read';
$modversion['config'][15]['title'] = '_MI_TADNEWS_DOWNLOAD_AFTER_READ';
$modversion['config'][15]['description'] = '_MI_TADNEWS_DOWNLOAD_AFTER_READ_DESC';
$modversion['config'][15]['formtype'] = 'yesno';
$modversion['config'][15]['valuetype'] = 'int';
$modversion['config'][15]['default'] = 0;


$modversion['config'][16]['name'] = 'creat_cate_group';
$modversion['config'][16]['title'] = '_MI_TADNEWS_CREAT_CATE_GROUP';
$modversion['config'][16]['description'] = '_MI_TADNEWS_CREAT_CATE_GROUP_DESC';
$modversion['config'][16]['formtype'] = 'group_multi';
$modversion['config'][16]['valuetype'] = 'array';
$modversion['config'][16]['default'] = array(1);

/*
$modversion['config'][17]['name'] = 'use_kcfinder';
$modversion['config'][17]['title'] = '_MI_TADNEWS_USE_KCFINDER';
$modversion['config'][17]['description'] = '_MI_TADNEWS_USE_KCFINDER_DESC';
$modversion['config'][17]['formtype'] = 'yesno';
$modversion['config'][17]['valuetype'] = 'int';
$modversion['config'][17]['default'] = 0;
*/

$modversion['config'][18]['name'] = 'summary_lengths';
$modversion['config'][18]['title'] = '_MI_TADNEWS_SUMMARY_LENGTHS';
$modversion['config'][18]['description'] = '_MI_TADNEWS_SUMMARY_LENGTHS_DESC';
$modversion['config'][18]['formtype'] = 'text';
$modversion['config'][18]['valuetype'] = 'int';
$modversion['config'][18]['default'] = 100;

$modversion['config'][19]['name'] = 'facebook_comments_width';
$modversion['config'][19]['title'] = '_MI_FBCOMMENT_TITLE';
$modversion['config'][19]['description'] = '_MI_FBCOMMENT_TITLE_DESC';
$modversion['config'][19]['formtype'] = 'yesno';
$modversion['config'][19]['valuetype'] = 'int';
$modversion['config'][19]['default'] = '1';

$modversion['config'][20]['name'] = 'use_pda';
$modversion['config'][20]['title'] = '_MI_USE_PDA_TITLE';
$modversion['config'][20]['description'] = '_MI_USE_PDA_TITLE_DESC';
$modversion['config'][20]['formtype'] = 'yesno';
$modversion['config'][20]['valuetype'] = 'int';
$modversion['config'][20]['default'] = '1';

$modversion['config'][21]['name'] = 'use_social_tools';
$modversion['config'][21]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][21]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][21]['formtype'] = 'yesno';
$modversion['config'][21]['valuetype'] = 'int';
$modversion['config'][21]['default'] = '1';


$modversion['config'][21]['name'] = 'use_social_tools';
$modversion['config'][21]['title'] = '_MI_SOCIALTOOLS_TITLE';
$modversion['config'][21]['description'] = '_MI_SOCIALTOOLS_TITLE_DESC';
$modversion['config'][21]['formtype'] = 'yesno';
$modversion['config'][21]['valuetype'] = 'int';
$modversion['config'][21]['default'] = '1';

$modversion['config'][23]['name'] = 'use_star_rating';
$modversion['config'][23]['title'] = '_MI_STAR_RATING_TITLE';
$modversion['config'][23]['description'] = '_MI_STAR_RATING_DESC';
$modversion['config'][23]['formtype'] = 'yesno';
$modversion['config'][23]['valuetype'] = 'int';
$modversion['config'][23]['default'] = '1';


$modversion['config'][24]['name'] = 'cover_pic_css';
$modversion['config'][24]['title'] = '_MI_COVER_PIC_CSS';
$modversion['config'][24]['description'] = '_MI_COVER_PIC_CSS_DESC';
$modversion['config'][24]['formtype'] = 'textarea';
$modversion['config'][24]['valuetype'] = 'text';
$modversion['config'][24]['default'] = 'width:200px; height:150px; border:1px solid #909090; background-position:left top; background-repeat:no-repeat; background-size:cover; float:right; margin:4px;';

$modversion['config'][25]['name'] = 'editor';
$modversion['config'][25]['title'] = '_MI_TADNEWS_EDITOR';
$modversion['config'][25]['description'] = '_MI_TADNEWS_EDITOR_DESC';
$modversion['config'][25]['formtype'] = 'select';
$modversion['config'][25]['valuetype'] = 'text';
$modversion['config'][25]['default'] = "ckeditor";
$modversion['config'][25]['options']	= array('CKEditor' => 'ckeditor','elRTE' => 'elrte');
?>
