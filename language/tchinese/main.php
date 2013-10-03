<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2007-11-04
// $Id: main.php,v 1.4 2008/06/25 06:40:17 tad Exp $
// ------------------------------------------------------------------------- //
//global
include_once "global.php";



define("_MD_TADNEWS_TO_MOD","回模組首頁");
define("_MD_TADNEWS_TO_ADMIN","管理介面");
define("_MD_TADNEWS_MY","我的文章");
define("_MD_TADNEWS_ADD_FIRST","目前沒有任何文章，立即新增第一篇文章！");
define("_MD_TADNEWS_NO_NEWS","目前沒有任何文章！");
define("_MD_TADNEWS_HIDDEN","本文已不開放！");
define("_MD_TADNEWS_OVERDUE","本文已過期！");
define("_MD_TADNEWS_NOT_GROUP","您所屬的群組沒有權限閱讀本文！");
define("_MD_TADNEWS_ALL_CATE","所有分類");
define("_MD_TADNEWS_FILES","附檔");
define("_MD_TADNEWS_POSTER","發佈者");
define("_MD_TADNEWS_FOR","：");
define("_TAD_NEED_TADTOOLS"," 需要 modules/tadtools，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");
define("_MD_TADNEWS_NEWS_PIC","上傳文章封面圖");
define("_MD_TADNEWS_ORDER_SUCCESS","訂閱「%s」完成！");
define("_MD_TADNEWS_ORDER_ERROR","訂閱「%s」失敗！");
define("_TADNEWS_DEL_SUCCESS","取消訂閱「%s」完成！");
define("_TADNEWS_DEL_ERROR","取消訂閱「%s」失敗！");
define("_MD_TADNEWS_NP_TITLE","第 %s 期");
define("_MD_TADNEWS_FILE_DL_NUM","（已被下載 %s 次）");
define("_MD_TADNEWS_ERROR_EMAIL","%s 不是合法的Email");

define("_MD_TADNEWS_POST","發佈文章");
define("_MD_TADNEWS_HAVE_READ_NUM","%s 人簽收");
define("_MD_TADNEWS_UID_NAME","用戶名稱");
define("_MD_TADNEWS_SIGN_TIME","簽收時間");
define("_MD_TADNEWS_SIGN_LOG","「%s」簽收紀錄");

//post.php
define("_MD_TADNEWS_NO_POST_POWER","尚未登入，無法發表文章。");
define("_MD_TADNEWS_ADD_NEWS","編輯文章");
define("_MD_TADNEWS_NEWS_TITLE","文章標題");
define("_MD_TADNEWS_PREFIX_TAG","標籤");
define("_MD_TADNEWS_ALWAYS_TOP","置頂");
define("_MD_TADNEWS_NEWS_CONTENT","文章內容");
define("_MD_TADNEWS_START_DATE","發佈時間");
define("_MD_TADNEWS_END_DATE","結束時間");
define("_MD_TADNEWS_NEWS_PASSWD","文章加密");
define("_MD_TADNEWS_ADV_SETUP","進階設定");
define("_MD_TADNEWS_SAVE_NEWS","儲存");
define("_MD_TADNEWS_CAN_READ_NEWS_GROUP","可讀取群組");
define("_MD_TADNEWS_DB_ADD_ERROR2","無法新增資料到tad_news中");
define("_MD_TADNEWS_DB_SELECT_ERROR2","無法取得tad_news資料");
define("_MD_TADNEWS_DB_UPDATE_ERROR2","無法更新tad_news中的資料");
define("_MD_TADNEWS_DB_DELETE_ERROR2","無法刪除tad_news中的資料");
define("_MD_TADNEWS_NEWS_CATE_TEXT","請選擇文章分類。<br>紅色是屬於「自訂頁面分類」");
define("_MD_TADNEWS_NEWS_TITLE_TEXT","請輸入文章標題");
define("_MD_TADNEWS_START_DATE_TEXT","設定文章發佈日期，<br>日期一到則立即發佈，<br>不設代表立即發佈。");
define("_MD_TADNEWS_END_DATE_TEXT","設定文章下架日期，<br>不設代表永遠顯示");
define("_MD_TADNEWS_NEWS_PASSWD_TEXT","有加密的文章<br>需輸入密碼才看得見");
define("_MD_TADNEWS_CAN_READ_NEWS_GROUP_TEXT","不選，或者選「全部開放」代表所有人都可以讀取本文章<br>亦可按住 Ctrl 來挑選只開放給哪些群組觀看。");
define("_MD_TADNEWS_NEWS_CATE","文章分類");
define("_MD_TADNEWS_SELECT_NEWS_CATE","請選擇文章分類");
define("_MD_TADNEWS_NEWS_ENABLE","公開或草稿");
define("_MD_TADNEWS_NEWS_ENABLE1_TEXT","選擇「公開」則會立即看見該文章");
define("_MD_TADNEWS_NEWS_ENABLE0_TEXT","選擇「草稿」則不會在文章列表中看見該文章");
define("_MD_TADNEWS_NEWS_ENABLE_OK","公開");
define("_MD_TADNEWS_NEWS_FILES","上傳附檔：");
define("_MD_TADNEWS_NEWS_FILES_LIST","附檔列表");
define("_MD_CAT_CANT_FIND_FILE","無法下載該檔");
define("_MD_TADNEWS_MON","月");
define("_MD_TADNEWS_1","一");
define("_MD_TADNEWS_2","二");
define("_MD_TADNEWS_3","三");
define("_MD_TADNEWS_4","四");
define("_MD_TADNEWS_5","五");
define("_MD_TADNEWS_6","六");
define("_MD_TADNEWS_7","日");
define("_MD_TADNEWS_WEEK","週");
define("_MD_TADNEWS_TODAY","今日");
define("_MD_TADNEWS_ALL_NO","不需簽收");
define("_MD_TADNEWS_NEWS_HAVE_READ","需簽收群組");
define("_MD_NEED_TADTOOLS","需要 modules/tadtools，可至<a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad教材網</a>下載。");
define("_MD_TADNEWS_CREAT_NEWS_CATE","在左邊分類下建立新分類");

//archive.php
define("_MD_TADNEWS_ARCHIVE","分月文章");
define("_MD_TADNEWS_YEAR","年");
define("_MD_TADNEWS_MONTH","月");


define("_MD_TADNEWS_NEWSPAPER","電子報列表");
define("_MD_TADNEWS_NEWSPAPER_LIST","==== 請選擇要觀看的電子報 ====");
define("_MD_TADNEWS_NP_DATE","發佈日期");
define("_MD_TADNEWS_NP_NUMBER","電子報期數");


define("_MD_TADNEWS_ALWAYS_TOP","置頂文章");
define("_MD_TADNEWS_ALWAYS_TOP","今日文章");



define("_MD_TADNEWS_TIME_TAB","發布時間");
define("_MD_TADNEWS_PRIVILEGE_TAB","權限");
define("_MD_TADNEWS_NEWSPIC_TAB","封面圖");
define("_MD_TADNEWS_FILES_TAB","附檔");
define("_MD_TADNEWS_ENABLE_NEWSPIC","是否顯示");
define("_MD_TADNEWS_ENABLE_NEWSPIC_NO","不要在內文中顯示封面圖");
define("_MD_TADNEWS_ENABLE_NEWSPIC_YES","要在內文中顯示封面圖（當作插圖用）");
define("_MD_TADNEWS_NEWSPIC_SIZE","圖片框尺寸");
define("_MD_TADNEWS_NEWSPIC_WIDTH","寬 x 高");
define("_MD_TADNEWS_NEWSPIC_BORDER","圖片邊框外觀");
define("_MD_TADNEWS_NEWSPIC_BORDER_WIDTH","粗細");
define("_MD_TADNEWS_NEWSPIC_BORDER_STYTLE","線條");
define("_MD_TADNEWS_NEWSPIC_SOLID","實線");
define("_MD_TADNEWS_NEWSPIC_DASHED","虛線");
define("_MD_TADNEWS_NEWSPIC_DOUBLE","雙線");
define("_MD_TADNEWS_NEWSPIC_DOTTED","點線");
define("_MD_TADNEWS_NEWSPIC_GROOVE","凹線");
define("_MD_TADNEWS_NEWSPIC_RIDGE","凸線");
define("_MD_TADNEWS_NEWSPIC_INSET","嵌入線");
define("_MD_TADNEWS_NEWSPIC_OUTSET","浮出線");
define("_MD_TADNEWS_NEWSPIC_NONE","無框線");
define("_MD_TADNEWS_NEWSPIC_BORDER_COLOR","顏色");
define("_MD_TADNEWS_NEWSPIC_FLOAT","圖片框位置");
define("_MD_TADNEWS_NEWSPIC_FLOAT_LEFT","靠左文繞圖");
define("_MD_TADNEWS_NEWSPIC_FLOAT_RIGHT","靠右文繞圖");
define("_MD_TADNEWS_NEWSPIC_FLOAT_NONE","不文繞圖");
define("_MD_TADNEWS_NEWSPIC_MARGIN","外邊界");
define("_MD_TADNEWS_NEWSPIC","封面圖的重複方式");
define("_MD_TADNEWS_NEWSPIC_NO_REPEAT","不重複");
define("_MD_TADNEWS_NEWSPIC_REPEAT","重複");
define("_MD_TADNEWS_NEWSPIC_X_REPEAT","水平重複");
define("_MD_TADNEWS_NEWSPIC_Y_REPEAT","垂直重複");
define("_MD_TADNEWS_NEWSPIC_SHOW","呈現封面圖的");
define("_MD_TADNEWS_NEWSPIC_AND","縮放封面圖");
define("_MD_TADNEWS_NEWSPIC_LEFT_TOP","左上");
define("_MD_TADNEWS_NEWSPIC_LEFT_CENTER","左中");
define("_MD_TADNEWS_NEWSPIC_LEFT_BOTTOM","左下");
define("_MD_TADNEWS_NEWSPIC_RIGHT_TOP","右上");
define("_MD_TADNEWS_NEWSPIC_RIGHT_CENTER","右中");
define("_MD_TADNEWS_NEWSPIC_RIGHT_BOTTOM","右下");
define("_MD_TADNEWS_NEWSPIC_CENTER_TOP","中上");
define("_MD_TADNEWS_NEWSPIC_CENTER_CENTER","中中");
define("_MD_TADNEWS_NEWSPIC_CENTER_BOTTOM","中下");
define("_MD_TADNEWS_NEWSPIC_NO_RESIZE","不做任何縮放");
define("_MD_TADNEWS_NEWSPIC_CONTAIN","縮放以看見完整封面圖");
define("_MD_TADNEWS_NEWSPIC_COVER","縮放到塞滿整個圖片框長邊");
define("_MD_TADNEWS_NEWSPIC_DEMO","<p>所謂「封面圖」指的就是替每一篇文章上傳一個具有代表性的圖片，此圖片會用在各個區塊上，以增加版面的活潑性。每個區塊的封面圖都可以自行去設定其大小及外觀。若是您想把封面圖也放到內文中當作插圖，那麼，您可以利用此界面來做設定。</p><p>封面圖並沒有一定要多大，但由於封面圖也可以用在滑動新聞區塊上作為大張的滑動圖，因此建議您，圖的大小至少比滑動區塊大即可，預設值為 670x250，因此，建議您，封面圖盡量在這個大小範圍為佳。</p>");


define("_MD_TADNEWS_TABLE_CONTENT_WIDTH","呈現寬度");
define("_MD_TADNEWS_MORE","觀看完整文章");
define("_MD_TADNEWS_DEL_FILE","選擇要刪除的檔案：");
?>
