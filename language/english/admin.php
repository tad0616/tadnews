<?php
//global.php
include_once 'global.php';

include_once "../../tadtools/language/{$xoopsConfig['language']}/admin_common.php";
define('_MA_TADNEWS_SAVE_CATE', 'Save');
define('_MA_TADNEWS_FUNCTION', 'Function');
define('_MA_TADNEWS_MOVE', 'Move');
define('_MA_TADNEWS_ADD', 'Post');
define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");
define('_MA_TADNEWS_NEWS_TITLE', 'Title');
define('_MA_TADNEWS_NEWS_PASSWD', 'Password');
define('_MA_TADNEWS_CAN_READ_NEWS_GROUP', 'Available Groups');
//<br > Non - select for publishing immediatelly . ');<br>Non-select for forever visible.') ;
define('_MA_TADNEWS_NEWS_CATE', 'Category');

//cate.php
define('_MA_TADNEWS_ADD_CATE', 'Add Category');
define('_MA_TADNEWS_ADD_PAGE_CATE', 'Add Customized Page Category');
define('_MA_TADNEWS_PARENT_CATE', 'Parent Category:');
define('_MA_TADNEWS_CATE_TITLE', 'Title');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP', 'Available groups to <span style="color:blue">read</span> this category');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP', 'Available groups to <span style="color:red">post</span> news in this category.');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP_TXT', 'None selected = All Free');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP_TXT', 'None selected = Administrator Only');
define('_MA_TADNEWS_DB_UPDATE_ERROR1', 'Failed to update data of tad_news_cate');
define('_MA_TADNEWS_DB_DEL_ERROR1', 'Can\'t delete tad_news_cate\'s news');
define('_MA_TADNEWS_CATE_COUNTER', 'Total: ');
define('_MA_TADNEWS_ONLY_ROOT', 'Administrator');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP_S', 'Read');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP_S', 'Post');
define('_MA_TADNEWS_NO', 'No (for customized pages with blocks.)');
define('_MA_TADNEWS_CATE_PIC', 'Icon');
define('_MA_TADNEWS_CHANGE_TO_NEWS', 'Set as News category');
define('_MA_TADNEWS_CHANGE_TO_PAGE', 'Set as customized pages category');

//import.php
define('_MA_TADNEWS_NO_NEWSMOD', 'News Module not installed; No data could be transferred.');
define('_MA_TADNEWS_HAVE_NEWSMOD', ' News Module installed version is %s');
define('_MA_TADNEWS_IMPORT_CATE', 'Select Category to be transferred');
define('_MA_TADNEWS_IMPORT', 'Start');

//newspaper.php
define('_MA_TADNEWS_NP', 'Newspaper');
define('_MA_TADNEWS_NP_SELECT', 'Create Newspaper');
define('_MA_TADNEWS_NP_MODIFY', 'Modify Header/Footer');
define('_MA_TADNEWS_NP_DEL', 'Delete');
define('_MA_TADNEWS_NP_DEL_DESC', 'Still %s data exist, failed to delete.');
define('_MA_TADNEWS_NP_OPTION', 'Select a title');
define('_MA_TADNEWS_NP_CREATE', 'Create a new title');
define('_MA_TADNEWS_NP_TITLE', 'Title');
define('_MA_TADNEWS_NP_DATE', 'Date');
define('_MA_TADNEWS_NP_NUMBER', 'Numbers');
define('_MA_TADNEWS_NP_NUMBER_INPUT', 'No. %s ');
define('_MA_TADNEWS_NP_STEP1', '[Step 1] Select or create a new title');
define('_MA_TADNEWS_NP_STEP2', '[Step 2] Select the News into newspaper');
define('_MA_TADNEWS_NP_STEP3', '[Step 3] Edit newspaper');
define('_MA_TADNEWS_NP_STEP4', '[Step 4] Submit');
define('_MA_TADNEWS_NP_CONTENT_HEAD', 'Header');
define('_MA_TADNEWS_NP_CONTENT_HEAD_DESC', '<p>Displayed on top of newspaper including words and pictures</p>
<p>{N}->"Number."</p>
<p>{T}→"Newspaper Title"</p>
<p>{D}->"Publish Date"</p>');
define('_MA_TADNEWS_NP_CONTENT', 'Content');
define('_MA_TADNEWS_NP_CONTENT_FOOT', 'Footer');
define('_MA_TADNEWS_NP_CONTENT_FOOT_DESC', 'Keep empty and click "Next" for default Header or Footer');
define('_MA_TADNEWS_NP_TITLE_L', '(');
define('_MA_TADNEWS_NP_TITLE_R', ')');

define('_MA_TADNEWS_NP_HEAD_CONTENT', "<h5 style=\"color:white;float:right;\">%s NO. {N} </h5><h1>{T}</h1><h2>* Subscribe: %s * Publish Date: {D}</h2>");

define('_MA_TADNEWS_NP_FOOT_CONTENT', "<div class=\"foot\"><h1>[About] </h1>
<p>* Editor: %s</p>
<p>All rights reserved by \"<a href='%s' target='_blank'>%s</a>\', and published in <a href='http://creativecommons.org/licenses/by-sa/2.5/tw/deed.zh_TW' target='_blank'>Creative Common CC\"Attribution licensing-Attribution-NoDerivs-Noncommercial\"article of authority Taiwan 2.5 version </a>. <a href='http://creativecommons.org/licenses/by-sa/2.5/tw/legalcode' target='_blank'>(Full Article of Authority)</a></p>
<p>To use any content of newspaper out of authorized range, please contact \"%s\"(<a href='mailto:%s'>%s</a>) </p>
<p>To subscribe or cancel newspaper, please go to :<a href='%s' target='_blank'>%s</a></p></div>");
define('_MA_TADNEWS_SEND_NOW', 'Submit');
define('_MA_TADNEWS_MAIL_LIST', 'Received (total: %s):');
define('_MA_TADNEWS_NP_LIST', 'Manage Newspaper');
define('_MA_TADNEWS_NP_THEMES', 'Theme');
define('_MA_TADNEWS_NP_EMAIL', 'Manage Email');
define('_MA_TADNEWS_NP_EMAIL_IMPORT', 'Import email');
define('_MA_TADNEWS_NEVER_SEND', 'Has not been sent');
define('_MA_TADNEWS_SEND_LOG', 'View log');
define('_MA_TADNEWS_EMPTY_LOG', 'No log');
define('_MA_TADNEWS_BACK_TO', 'Back to %s');
define('_MA_TADNEWS_NP_SUB_TITLE', 'Newspaper title: ');


//page
define('_MA_TADNEWS_CATE_SHOW_TITLE', 'Show News Title');
define('_MA_TADNEWS_CATE_SHOW_TOOL', 'Show Toolbar');
define('_MA_TADNEWS_CATE_SHOW_COMM', 'Comments Available');
define('_MA_TADNEWS_CATE_SHOW_NAV', 'Show Navigation Button');

//tag.php
define('_MA_TADNEWS_TAG_TITLE', 'Tag');
define('_MA_TADNEWS_TAG_FONTCOLOR', 'Text color');
define('_MA_TADNEWS_TAG_COLOR', 'Color');
define('_MA_TADNEWS_TAG_ENABLE', 'Enable?');
define('_MA_TADNEWS_TAG_DEMO', 'Demo');
define('_MA_TADNEWS_TAG_FUNC', 'Function');
define('_MA_TADNEWS_TAG_NEW', 'New Tag');
define('_MA_TADNEWS_TAG_ABLE', 'Enable');
define('_MA_TADNEWS_TAG_UNABLE', 'Unable');
define('_MA_TADNEWS_TAG_AMOUNT', ', %s news use it.');
