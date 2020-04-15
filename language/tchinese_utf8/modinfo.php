<?php
xoops_loadLanguage('modinfo_common', 'tadtools');

define('_MI_TADNEWS_ADMIN_HOME', '首頁');
define('_MI_TADNEWS_ADMIN_HOME_DESC', '回首頁');

define('_MI_TADNEWS_NAME', '本站消息');
define('_MI_TADNEWS_DESC', '一個容易使用的新聞模組');
define('_MI_TADNEWS_WEB', 'Tad 教材網');
define('_MI_TADNEWS_ADMENU1', '文章管理');
define('_MI_TADNEWS_ADMENU2', '發佈文章');
define('_MI_TADNEWS_ADMENU3', '分類管理');
define('_MI_TADNEWS_ADMENU4', '資料轉移');
define('_MI_TADNEWS_ADMENU5', '電子報管理');
define('_MI_TADNEWS_ADMENU7', '自訂頁面');
define('_MI_TADNEWS_ADMENU8', '標籤管理');
define('_MI_TADNEWS_ADMENU9', '自訂頁面分類');
define('_MI_TADNEWS_NEWSPAPER', '電子報列表');
define('_MI_TADNEWS_ARCHIVE', '分月文章');

define('_MI_TADNEWS_BNAME1', '文章類別');
define('_MI_TADNEWS_BDESC1', '顯示所有文章的類別');
define('_MI_TADNEWS_BNAME2', '本站最新消息');
define('_MI_TADNEWS_BDESC2', '顯示文章內容');
define('_MI_TADNEWS_BNAME3', '最新回應');
define('_MI_TADNEWS_BDESC3', '列出最新的文章評論');
define('_MI_TADNEWS_BNAME4', '訂閱 / 取消電子報');
define('_MI_TADNEWS_BDESC4', '讓使者訂閱電子報的區塊');
define('_MI_TADNEWS_BNAME5', '電子報一覽');
define('_MI_TADNEWS_BDESC5', '秀出最新的電子報');
define('_MI_TADNEWS_BNAME6', '分類新聞區塊');
define('_MI_TADNEWS_BDESC6', '秀出指定分類最新的新聞');
define('_MI_TADNEWS_BNAME7', '自訂頁面樹狀目錄');
define('_MI_TADNEWS_BDESC7', '秀出指定的自訂頁面分類所有文章標題');
define('_MI_TADNEWS_BNAME8', '焦點新聞');
define('_MI_TADNEWS_BDESC8', '可以自訂要播放哪些新聞');

define('_MI_TADNEWS_TITLE1', '<b>每頁要秀出幾篇文章？</b>');
define('_MI_TADNEWS_DESC1', '設定模組頁面出現的文章數');

define('_MI_TADNEWS_SHOW_MODE', '<b>設定模組首頁的呈現方式</b>');
define('_MI_TADNEWS_SHOW_MODE_DESC', '可自由選擇要秀出文章摘要（原有模式），或者只秀出文章標題（較快，較簡潔）');
define('_MI_TADNEWS_SHOW_MODE_OPT1', '秀出摘要或本文');
define('_MI_TADNEWS_SHOW_MODE_OPT2', '僅秀出標題列表');
define('_MI_TADNEWS_SHOW_MODE_OPT3', '顯示各個分類的新聞標題');

define('_MI_TADNEWS_CATE_SHOW_MODE', '<b>設定秀出分類新聞的呈現方式</b>');
define('_MI_TADNEWS_CATE_SHOW_MODE_DESC', '點選分類後，新聞要呈現的模式，可自由選擇要秀出文章摘要（原有模式），或者只秀出文章標題（較快，較簡潔）');

define('_MI_TADNEWS_SHOW_BB', '<b>是否顯示出「BB Code」的連結</b>');
define('_MI_TADNEWS_SHOW_BB_DESC', '一般為「否」。早期的 news 模組內容會用 BB Code 來設定文字外觀，例如顏色、大小等。若是您的新聞是早期從 news 轉移過來的，而且有用大量的 BB Code，那麼可選「是」。');

define('_MI_TADNEWS_CATE_PIC_WIDTH', '<b>分類圖片寬度</b>');
define('_MI_TADNEWS_CATE_PIC_WIDTH_DESC', '上傳分類圖檔時，將以此寬度為縮圖寬度依據。');

define('_MI_TADNEWS_PIC_WIDTH', '<b>新聞圖片附檔寬度</b>');
define('_MI_TADNEWS_PIC_WIDTH_DESC', '新聞圖片附檔寬度，以px為單位');

define('_MI_TADNEWS_THUMB_WIDTH', '<b>新聞圖片附檔縮圖寬度</b>');
define('_MI_TADNEWS_THUMB_WIDTH_DESC', '新聞圖片附檔縮圖寬度，以px為單位');

//tadnew 1.4
define('_MI_TADNEWS_BNAME9', '自選文章');
define('_MI_TADNEWS_BDESC9', '可以自己選定文章');
define('_MI_TADNEWS_USE_NEWSPAPER', '前台選單秀出「電子報」項目');
define('_MI_TADNEWS_USE_NEWSPAPER_DESC', '若否，「電子報」項目不會出現在前台主選單及頁面選單上');
define('_MI_TADNEWS_USE_USE_ARCHIVE', '前台選單秀出「分月文章」項目');
define('_MI_TADNEWS_USE_USE_ARCHIVE_DESC', '若否，「分月文章」項目不會出現在前台主選單及頁面選單上');
define('_MI_TADNEWS_SHOW_SUBMENU', '是否顯示子選單');
define('_MI_TADNEWS_SHOW_SUBMENU_DESC', '是否顯示子選單以及功能表');

//tadnews 2.0
define('_MI_TADNEWS_DOWNLOAD_AFTER_READ', '簽收後始能下載檔案');
define('_MI_TADNEWS_DOWNLOAD_AFTER_READ_DESC', '若該新聞是需要簽收的，必須簽收後才能下載附檔。');
define('_MI_TADNEWS_CREAT_CATE_GROUP', '可直接開設分類的群組');
define('_MI_TADNEWS_CREAT_CATE_GROUP_DESC', '設定可以發文直接開設分類的群組');
define('_MI_TADNEWS_BNAME10', '條列式新聞');
define('_MI_TADNEWS_BDESC10', '文章標題條列式');
define('_MI_TADNEWS_BNAME11', '表格式新聞');
define('_MI_TADNEWS_BDESC11', '表格佈告欄式');
define('_MI_TADNEWS_SUMMARY_LENGTHS', '摘要長度');
define('_MI_TADNEWS_SUMMARY_LENGTHS_DESC', '選用條列式呈現新聞時，要秀出的摘要字數');

define('_MI_TADNEWS_BNAME12', 'QR Code');
define('_MI_TADNEWS_BDESC12', '產生連到手持裝置頁面的QR Code條碼');

define('_MI_TADNEWS_BNAME13', '滑動新聞');
define('_MI_TADNEWS_BDESC13', '以文章封面圖為底的滑動新聞');
define('_MI_TADNEWS_BNAME14', '自動縮放的滑動新聞');
define('_MI_TADNEWS_BDESC14', '以文章封面圖為底的滑動新聞（可視裝置自動縮放）');

define('_MI_COVER_PIC_CSS', '內文封面圖的CSS預設值');
define('_MI_COVER_PIC_CSS_DESC', '將封面圖插入內文時的外觀CSS預設值');

define('_MI_TADNEWS_EDITOR', '請選擇欲使用的編輯器');
define('_MI_TADNEWS_EDITOR_DESC', 'elRTE 可用於手機等行動裝置，CKEditor則不行');

define('_MI_TADNEWS_FANCYBOX_SPEED', '設定附檔的自動播放速度');
define('_MI_TADNEWS_FANCYBOX_SPEED_DESC', '預設為 5000（5秒）自動播放，設成 0 即不自動播放。');

define('_MI_TADNEWS_MARQUEE', '跑馬燈區塊');
define('_MI_TADNEWS_MARQUEE_DESC', '相當於條列新聞做成跑馬燈之意');

define('_MI_TADNEWS_COVERED', '圖文集區塊');
define('_MI_TADNEWS_COVERED_DESC', '以封面圖為主，搭配簡短文字的區塊');

define('_MI_TADNEWS_PAGE_LIST', '自訂頁面列表');
define('_MI_TADNEWS_PAGE_LIST_DESC', '自訂頁面列表');

define('_MI_TADNEWS_PAGE_MENU', '自訂頁面選單');
define('_MI_TADNEWS_PAGE_MENU_DESC', '自訂頁面選單');

define('_MI_TADNEWS_USE_TOP_GROUP', '可使用置頂功能的群組');
define('_MI_TADNEWS_USE_TOP_GROUP_DESC', '可使用置頂功能的群組');

define('_MI_TADNEWS_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TADNEWS_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_TADNEWS_BACK_2_ADMIN', '管理');

//help
define('_MI_TADNEWS_HELP_OVERVIEW', '概要');

define('_MI_TADNEWS_TAB_NEWS', '頁籤新聞區塊');
define('_MI_TADNEWS_TAB_NEWS_DESC', '頁籤新聞區塊');
define('_MI_TADNEWS_TAG_NEWS', '標籤新聞區塊');
define('_MI_TADNEWS_TAG_NEWS_DESC', '標籤新聞區塊');
define('_MI_TADNEWS_TOP_MAX_DAY', '置頂日數上限');
define('_MI_TADNEWS_TOP_MAX_DAY_DESC', '新聞最多可以置頂幾天？');

define('_MI_TADNEWS_UPLOAD_DENY', '不允許上傳的檔案副檔名');
define('_MI_TADNEWS_UPLOAD_DENY_DESC', '請用分號隔開，例如：「doc;docx;doc;xls;xlsx」');
