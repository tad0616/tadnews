<?php
xoops_loadLanguage('main', 'tadtools');
require_once __DIR__ . '/global.php';

if (!defined('_TAD_NEED_TADTOOLS')) {
    define('_TAD_NEED_TADTOOLS', ' 需要 modules/tadtools，可至<a href="http://campus-xoops.tn.edu.tw/modules/tad_modules/index.php?module_sn=1" target="_blank">XOOPS輕鬆架</a>下載。');
}
define('_MD_TADNEWS_TO_MOD', '回模組首頁');
define('_MD_TADNEWS_TO_ADMIN', '管理介面');
define('_MD_TADNEWS_MY', '我的文章');
define('_MD_TADNEWS_HIDDEN', '本文已不開放！');
define('_MD_TADNEWS_ALL_CATE', '所有分類');
define('_MD_TADNEWS_FILES', '附檔');
define('_MD_TADNEWS_POSTER', '發佈者');
define('_MD_TADNEWS_FOR', '：');
define('_MD_TADNEWS_NEWS_PIC', '上傳文章封面圖');
define('_MD_TADNEWS_ORDER_SUCCESS', '訂閱「%s」完成！');
define('_MD_TADNEWS_ORDER_ERROR', '訂閱「%s」失敗！');
define('_TADNEWS_DEL_SUCCESS', '取消訂閱「%s」完成！');
define('_TADNEWS_DEL_ERROR', '取消訂閱「%s」失敗！');
define('_MD_TADNEWS_NP_TITLE', '第 %s 期');
define('_MD_TADNEWS_ERROR_EMAIL', '%s 不是合法的Email');

define('_MD_TADNEWS_POST', '發佈文章');
define('_MD_TADNEWS_SIGN_LOG', '「%s」簽收紀錄');

//post.php
define('_MD_TADNEWS_NO_POST_POWER', '尚未登入，無法發表文章。');
define('_MD_TADNEWS_ADD_NEWS', '編輯文章');
define('_MD_TADNEWS_NEWS_TITLE', '文章標題');
define('_MD_TADNEWS_ALWAYS_TOP', '置頂');
define('_MD_TADNEWS_START_DATE', '發佈時間');
define('_MD_TADNEWS_END_DATE', '結束時間');
define('_MD_TADNEWS_NEWS_PASSWD', '文章加密');
define('_MD_TADNEWS_ADV_SETUP', '進階設定');
define('_MD_TADNEWS_SAVE_NEWS', '儲存');
define('_MD_TADNEWS_CAN_READ_NEWS_GROUP', '可讀取群組');
define('_MD_TADNEWS_NEWS_CATE', '文章分類');
define('_MD_TADNEWS_NEWS_ENABLE', '公開或草稿');
define('_MD_TADNEWS_NEWS_ENABLE_OK', '公開');
define('_MD_TADNEWS_NEWS_FILES', '上傳附檔：');
define('_MD_TADNEWS_MON', '月');
define('_MD_TADNEWS_NEWS_HAVE_READ', '需簽收群組');

//archive.php
define('_MD_TADNEWS_ARCHIVE', '分月文章');
define('_MD_TADNEWS_YEAR', '年');
define('_MD_TADNEWS_MONTH', '月');

define('_MD_TADNEWS_NEWSPAPER', '電子報列表');

define('_MD_TADNEWS_TIME_TAB', '發布時間');
define('_MD_TADNEWS_PRIVILEGE_TAB', '權限');
define('_MD_TADNEWS_NEWSPIC_TAB', '封面圖');
define('_MD_TADNEWS_FILES_TAB', '附檔');
define('_MD_TADNEWS_ENABLE_NEWSPIC', '是否顯示');
define('_MD_TADNEWS_ENABLE_NEWSPIC_NO', '不要在內文中顯示封面圖');
define('_MD_TADNEWS_ENABLE_NEWSPIC_YES', '要在內文中顯示封面圖（當作插圖用）');
define('_MD_TADNEWS_NEWSPIC_WIDTH', '寬 x 高');
define('_MD_TADNEWS_NEWSPIC_BORDER', '圖片邊框外觀');
define('_MD_TADNEWS_NEWSPIC_BORDER_WIDTH', '粗細');
define('_MD_TADNEWS_NEWSPIC_BORDER_STYTLE', '線條');
define('_MD_TADNEWS_NEWSPIC_SOLID', '實線');
define('_MD_TADNEWS_NEWSPIC_DASHED', '虛線');
define('_MD_TADNEWS_NEWSPIC_DOUBLE', '雙線');
define('_MD_TADNEWS_NEWSPIC_DOTTED', '點線');
define('_MD_TADNEWS_NEWSPIC_GROOVE', '凹線');
define('_MD_TADNEWS_NEWSPIC_RIDGE', '凸線');
define('_MD_TADNEWS_NEWSPIC_INSET', '嵌入線');
define('_MD_TADNEWS_NEWSPIC_OUTSET', '浮出線');
define('_MD_TADNEWS_NEWSPIC_NONE', '無框線');
define('_MD_TADNEWS_NEWSPIC_BORDER_COLOR', '顏色');
define('_MD_TADNEWS_NEWSPIC_FLOAT', '圖片框位置');
define('_MD_TADNEWS_NEWSPIC_FLOAT_LEFT', '靠左文繞圖');
define('_MD_TADNEWS_NEWSPIC_FLOAT_RIGHT', '靠右文繞圖');
define('_MD_TADNEWS_NEWSPIC_FLOAT_NONE', '不文繞圖');
define('_MD_TADNEWS_NEWSPIC_MDRGIN', '外邊界');
define('_MD_TADNEWS_NEWSPIC', '封面圖的重複方式');
define('_MD_TADNEWS_NEWSPIC_NO_REPEAT', '不重複');
define('_MD_TADNEWS_NEWSPIC_REPEAT', '重複');
define('_MD_TADNEWS_NEWSPIC_X_REPEAT', '水平重複');
define('_MD_TADNEWS_NEWSPIC_Y_REPEAT', '垂直重複');
define('_MD_TADNEWS_NEWSPIC_SHOW', '呈現封面圖的');
define('_MD_TADNEWS_NEWSPIC_AND', '縮放封面圖');
define('_MD_TADNEWS_NEWSPIC_LEFT_TOP', '左上');
define('_MD_TADNEWS_NEWSPIC_LEFT_CENTER', '左中');
define('_MD_TADNEWS_NEWSPIC_LEFT_BOTTOM', '左下');
define('_MD_TADNEWS_NEWSPIC_RIGHT_TOP', '右上');
define('_MD_TADNEWS_NEWSPIC_RIGHT_CENTER', '右中');
define('_MD_TADNEWS_NEWSPIC_RIGHT_BOTTOM', '右下');
define('_MD_TADNEWS_NEWSPIC_CENTER_TOP', '中上');
define('_MD_TADNEWS_NEWSPIC_CENTER_CENTER', '中中');
define('_MD_TADNEWS_NEWSPIC_CENTER_BOTTOM', '中下');
define('_MD_TADNEWS_NEWSPIC_NO_RESIZE', '不做任何縮放');
define('_MD_TADNEWS_NEWSPIC_CONTAIN', '縮放以看見完整封面圖');
define('_MD_TADNEWS_NEWSPIC_COVER', '縮放到塞滿整個圖片框長邊');
define('_MD_TADNEWS_NEWSPIC_DEMO', '<p>所謂「封面圖」指的就是替每一篇文章上傳一個具有代表性的圖片，此圖片會用在各個區塊上，以增加版面的活潑性。每個區塊的封面圖都可以自行去設定其大小及外觀。若是您想把封面圖也放到內文中當作插圖，那麼，您可以利用此界面來做設定。</p><p>封面圖並沒有一定要多大，但由於封面圖也可以用在滑動新聞區塊上作為大張的滑動圖，因此建議您，圖的大小至少比滑動區塊大即可，預設值為 670x250，因此，建議您，封面圖盡量在這個大小範圍為佳。</p>');
define('_MD_TADNEWS_COUNTER', '人氣');
define('_MD_TADNEWS_KIND_NEWS', '新聞文章');
define('_MD_TADNEWS_KIND_PAGE', '自訂頁面');
define('_MD_TADNEWS_KIND', '發布文章種類：');
define('_MD_TADNEWS_ONLY_ROOT', '僅管理員');
define('_MD_TADNEWS_EDIT_CATE', '編輯此分類');
define('_MD_TADNEWS_ADD_TO_MENU', '加入佈景選單');
define('_MD_TADNEWS_ADD_TO_MENU_ALERT', '「%s」已經加入導覽列選單，若欲將選單移除或變動位置，請直接從「<a href="' . XOOPS_URL . '/modules/tad_themes/admin/dropdown.php">選單設定</a>」管理之即可。');
// <{$smarty.const._MD_TADNEWS_ADD_TO_MENU_ALERT|sprintf:$k}>

define('_MD_TADNEWS_USE_TAB_MODE', '使用頁籤模式');

define('_MD_TADNEWS_TAB_TITLE', '請輸入頁籤 %s 標題');
define('_MD_TADNEWS_ADD_TAB', '新增頁籤');
define('_MD_TADNEWS_DEL_TAB', '移除頁籤');
define('_MD_TADNEWS_DELETE_TAB', '刪除「%s」除頁籤');

define('_MD_TADNEWS_TAB_TITLE1', '請輸入頁籤');
define('_MD_TADNEWS_TAB_TITLE2', '標題');

//cate.php
define('_MD_TADNEWS_ADD_CATE', '建立文章分類');
define('_MD_TADNEWS_ADD_PAGE_CATE', '建立自定頁面分類');
define('_MD_TADNEWS_PARENT_CATE', '放在此分類底下：');
define('_MD_TADNEWS_CATE_TITLE', '分類名稱');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP', '可<span style="color: blue;">讀文章</span>群組');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP', '可<span style="color: red;">發文章</span>群組');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP_TXT', '不選=全部可讀');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP_TXT', '不選=僅站長可發');
define('_MD_TADNEWS_DB_UPDATE_ERROR1', '無法更新tad_news_cate資料');
define('_MD_TADNEWS_DB_DEL_ERROR1', '無法刪除tad_news_cate資料');
define('_MD_TADNEWS_CATE_COUNTER', '文章數');
define('_MD_TADNEWS_CAN_READ_CATE_GROUP_S', '可讀取群組');
define('_MD_TADNEWS_CAN_POST_CATE_GROUP_S', '可管理群組');
define('_MD_TADNEWS_NO', '否（自訂頁面的用法，搭配區塊使用）');
define('_MD_TADNEWS_CATE_PIC', '分類圖片');
define('_MD_TADNEWS_CHANGE_TO_NEWS', '轉為新聞分類');
define('_MD_TADNEWS_CHANGE_TO_PAGE', '轉為自訂頁面分類');
define('_MD_TADNEWS_CATE_SHOW_TITLE', '顯示文章標題');
define('_MD_TADNEWS_CATE_SHOW_TOOL', '顯示模組工具');
define('_MD_TADNEWS_CATE_SHOW_COMM', '使用評論功能');
define('_MD_TADNEWS_CATE_SHOW_NAV', '使用上下頁鈕');
define('_MD_TADNEWS_CATE_SHOW_PATH', '顯示頁面路徑');

define('_MD_TADNEWS_DENY_TYPE', '（以下副檔名無法上傳：%s）');
define('_MD_TADNEWS_AND', '、');
