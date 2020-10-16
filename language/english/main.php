<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';
if (!defined('_TAD_NEED_TADTOOLS')) {
    define('_TAD_NEED_TADTOOLS', 'This module needs TadTools module. You can download TadTools from <a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS EasyGO</a>.');
}

define('_MD_TADNEWS_TO_MOD', 'Back to Module');
define('_MD_TADNEWS_TO_ADMIN', 'Admin');
define('_MD_TADNEWS_MY', 'My News');
define('_MD_TADNEWS_HIDDEN', 'No public access for this News so far!');
define('_MD_TADNEWS_ALL_CATE', 'All Categories');
define('_MD_TADNEWS_FILES', 'Attachments');
define('_MD_TADNEWS_POSTER', 'Poster');
define('_MD_TADNEWS_FOR', ': ');
define('_MD_TADNEWS_NEWS_PIC', 'Upload news cover image');
define('_MD_TADNEWS_ORDER_SUCCESS', '"%s" subscribed!');
define('_MD_TADNEWS_ORDER_ERROR', 'Scbscribe to "%s" failed!');
define('_TADNEWS_DEL_SUCCESS', 'Subscription to "%s" cancelled!');
define('_TADNEWS_DEL_ERROR', 'Failed to cancel "%s" subscription!');
define('_MD_TADNEWS_NP_TITLE', 'NO: %s ');
define('_MD_TADNEWS_ERROR_EMAIL', 'Illegal Email: %s');

define('_MD_TADNEWS_POST', 'Post');
define('_MD_TADNEWS_SIGN_LOG', '"%s" read log');

//post.php
define('_MD_TADNEWS_NO_POST_POWER', 'Please login first for posting news.');
define('_MD_TADNEWS_ADD_NEWS', 'Edit News');
define('_MD_TADNEWS_NEWS_TITLE', 'Title');
define('_MD_TADNEWS_ALWAYS_TOP', 'Sticky');
define('_MD_TADNEWS_START_DATE', 'Start Date');
define('_MD_TADNEWS_END_DATE', 'End Date');
define('_MD_TADNEWS_NEWS_PASSWD', 'Password');
define('_MD_TADNEWS_ADV_SETUP', 'Advance Settings');
define('_MD_TADNEWS_SAVE_NEWS', 'Save');
define('_MD_TADNEWS_CAN_READ_NEWS_GROUP', 'Available Groups');
//<br > Non - select for publishing immediatelly . ');<br>Non-select for forever visible.') ;
define('_MD_TADNEWS_NEWS_CATE', 'Category');
define('_MD_TADNEWS_NEWS_ENABLE', 'Save as');
define('_MD_TADNEWS_NEWS_ENABLE_OK', 'Public');

define('_MD_TADNEWS_NEWS_FILES', 'Upload files:');
define('_MD_TADNEWS_MON', '(Month)');
define('_MD_TADNEWS_NEWS_HAVE_READ', 'Must read Groups');

//archive.php
define('_MD_TADNEWS_ARCHIVE', 'Archive');
define('_MD_TADNEWS_YEAR', 'Year');
define('_MD_TADNEWS_MONTH', 'Month');

define('_MD_TADNEWS_NEWSPAPER', 'Newspaper List');

define('_MD_TADNEWS_TIME_TAB', 'Post time');
define('_MD_TADNEWS_PRIVILEGE_TAB', 'Privilege');
define('_MD_TADNEWS_NEWSPIC_TAB', 'Cover image');
define('_MD_TADNEWS_FILES_TAB', 'Files');
define('_MD_TADNEWS_ENABLE_NEWSPIC', 'Display in article?');
define('_MD_TADNEWS_ENABLE_NEWSPIC_NO', 'NO');
define('_MD_TADNEWS_ENABLE_NEWSPIC_YES', 'YES');
define('_MD_TADNEWS_NEWSPIC_WIDTH', 'Width and height');
define('_MD_TADNEWS_NEWSPIC_BORDER', 'Border');
define('_MD_TADNEWS_NEWSPIC_BORDER_WIDTH', 'Width:');
define('_MD_TADNEWS_NEWSPIC_BORDER_STYTLE', 'Style:');
define('_MD_TADNEWS_NEWSPIC_SOLID', 'Solid');
define('_MD_TADNEWS_NEWSPIC_DASHED', 'Dashed');
define('_MD_TADNEWS_NEWSPIC_DOUBLE', 'Double');
define('_MD_TADNEWS_NEWSPIC_DOTTED', 'Dotted');
define('_MD_TADNEWS_NEWSPIC_GROOVE', 'Groove');
define('_MD_TADNEWS_NEWSPIC_RIDGE', 'Ridge');
define('_MD_TADNEWS_NEWSPIC_INSET', 'Inset');
define('_MD_TADNEWS_NEWSPIC_OUTSET', 'Outset');
define('_MD_TADNEWS_NEWSPIC_NONE', 'None');
define('_MD_TADNEWS_NEWSPIC_BORDER_COLOR', 'Color:');
define('_MD_TADNEWS_NEWSPIC_FLOAT', 'Float');
define('_MD_TADNEWS_NEWSPIC_FLOAT_LEFT', 'Left');
define('_MD_TADNEWS_NEWSPIC_FLOAT_RIGHT', 'Right');
define('_MD_TADNEWS_NEWSPIC_FLOAT_NONE', 'None');
define('_MD_TADNEWS_NEWSPIC_MDRGIN', 'Wargin:');
define('_MD_TADNEWS_NEWSPIC', 'Image repeat:');
define('_MD_TADNEWS_NEWSPIC_NO_REPEAT', 'No-Repeat');
define('_MD_TADNEWS_NEWSPIC_REPEAT', 'Repeat');
define('_MD_TADNEWS_NEWSPIC_X_REPEAT', 'X-Repeat');
define('_MD_TADNEWS_NEWSPIC_Y_REPEAT', 'Y-Repeat');
define('_MD_TADNEWS_NEWSPIC_SHOW', ', Position:');
define('_MD_TADNEWS_NEWSPIC_LEFT_TOP', 'Left top');
define('_MD_TADNEWS_NEWSPIC_LEFT_CENTER', 'Left center');
define('_MD_TADNEWS_NEWSPIC_LEFT_BOTTOM', 'Left bottom');
define('_MD_TADNEWS_NEWSPIC_RIGHT_TOP', 'Right top');
define('_MD_TADNEWS_NEWSPIC_RIGHT_CENTER', 'Right center');
define('_MD_TADNEWS_NEWSPIC_RIGHT_BOTTOM', 'Right bottom');
define('_MD_TADNEWS_NEWSPIC_CENTER_TOP', 'Center top');
define('_MD_TADNEWS_NEWSPIC_CENTER_CENTER', 'Center center');
define('_MD_TADNEWS_NEWSPIC_CENTER_BOTTOM', 'Center bottom');
define('_MD_TADNEWS_NEWSPIC_AND', ', Image-size');
define('_MD_TADNEWS_NEWSPIC_NO_RESIZE', 'No Resize');
define('_MD_TADNEWS_NEWSPIC_CONTAIN', 'Contain');
define('_MD_TADNEWS_NEWSPIC_COVER', 'Cover');
define('_MD_TADNEWS_NEWSPIC_DEMO', '<p>Each article could have a cover picture, this picture can be used for some block. Each block can set its size and appearance. If you want to use cover picture in the artical, then you can use this interface to make setting.</p><p>You can upload any size image for cover picture , we recommend that  cover picture size is large than  slide news block width.  The  slide news block size  is 670x250. Therefore, we recommend that cover picture is large than  670x250.</p>');

//define('_MD_TADNEWS_EMBED','embed');
define('_MD_TADNEWS_COUNTER', 'Count');
define('_MD_TADNEWS_KIND_NEWS', 'News');
define('_MD_TADNEWS_KIND_PAGE', 'Page');
define('_MD_TADNEWS_KIND', 'Article kind:');
define('_MD_TADNEWS_ONLY_ROOT', 'Administrator');
define('_MD_TADNEWS_EDIT_CATE', 'Edit Category');
define('_MD_TADNEWS_ADD_TO_MENU', 'Add Category to Navigation');
define('_MD_TADNEWS_ADD_TO_MENU_ALERT', '"%s" has been added to the navigation bar menu. If you want to remove or change the position of the menu, from the management to the "<a href="' . XOOPS_URL . '/modules/tad_themes/admin/dropdown.php">Menu Settings</a>" directly.');

define('_MD_TADNEWS_USE_TAB_MODE', 'Use tab mode');

define('_MD_TADNEWS_TAB_TITLE', 'Please enter the tab %s title');
// <{$smarty.const._MD_TADNEWS_TAB_TITLE|sprintf:$k}>
define('_MD_TADNEWS_ADD_TAB', 'Add tab');
define('_MD_TADNEWS_DEL_TAB', 'Remove tab');
define('_MD_TADNEWS_DELETE_TAB', 'Remove "%s" tab');

define('_MD_TADNEWS_TAB_TITLE1', 'Please enter the tab');
define('_MD_TADNEWS_TAB_TITLE2', 'title');

//cate.php
define('_MD_TADNEWS_ADD_CATE', 'Add Category');
define('_MD_TADNEWS_ADD_PAGE_CATE', 'Add Customized Page Category');
define('_MD_TADNEWS_PARENT_CATE', 'Parent Category:');
define('_MD_TADNEWS_CATE_TITLE', 'Title');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP', 'Available groups to <span style="color:blue">read</span> this category');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP', 'Available groups to <span style="color:red">post</span> news in this category.');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP_TXT', 'None selected = All Free');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP_TXT', 'None selected = Administrator Only');
define('_MD_TADNEWS_DB_UPDATE_ERROR1', 'Failed to update data of tad_news_cate');
define('_MD_TADNEWS_DB_DEL_ERROR1', 'Can\'t delete tad_news_cate\'s news');
define('_MD_TADNEWS_CATE_COUNTER', 'Total: ');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP_S', 'Read');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP_S', 'Post');
define('_MD_TADNEWS_NO', 'No (for customized pages with blocks.)');
define('_MD_TADNEWS_CATE_PIC', 'Icon');
define('_MD_TADNEWS_CHANGE_TO_NEWS', 'Set as News category');
define('_MD_TADNEWS_CHANGE_TO_PAGE', 'Set as customized pages category');
define('_MD_TADNEWS_CATE_SHOW_TITLE', 'Show News Title');
define('_MD_TADNEWS_CATE_SHOW_TOOL', 'Show Module Toolbar');
define('_MD_TADNEWS_CATE_SHOW_COMM', 'Comments Available');
define('_MD_TADNEWS_CATE_SHOW_NAV', 'Show Navigation Button');
define('_MD_TADNEWS_CATE_SHOW_PATH', 'Show breadcrumbs');

define('_MD_TADNEWS_DENY_TYPE', '(The following file name is not uploadable: %s)');
define('_MD_TADNEWS_AND', ', ');
