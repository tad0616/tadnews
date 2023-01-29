<?php
xoops_loadLanguage('admin_common', 'tadtools');

require_once __DIR__ . '/global.php';
define('_MA_TADNEWS_SAVE_CATE', '儲存');
define('_MA_TADNEWS_FUNCTION', '功能');
define('_MA_TADNEWS_MOVE', '搬移文章');
define('_MA_TADNEWS_ADD', '新增文章');
define('_MA_TADNEWS_NEWS_TITLE', '文章標題');
define('_MA_TADNEWS_NEWS_PASSWD', '文章加密');
define('_MA_TADNEWS_CAN_READ_NEWS_GROUP', '可讀取群組');
define('_MA_TADNEWS_NEWS_CATE', '所屬分類');
define('_MA_TADNEWS_CHECK_ALL', '全選');
define('_MA_TADNEWS_NO_NEWS', '該分類沒有任何文章');
define('_MA_TADNEWS_ALL_NEWS', '所有文章');

//cate.php
define('_MA_TADNEWS_ADD_CATE', '建立文章分類');
define('_MA_TADNEWS_ADD_PAGE_CATE', '建立自定頁面分類');
define('_MA_TADNEWS_PARENT_CATE', '放在此分類底下：');
define('_MA_TADNEWS_CATE_TITLE', '分類名稱');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP', '可<span style="color: blue;">讀文章</span>群組');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP', '可<span style="color: red;">發文章</span>群組');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP_TXT', '不選=全部可讀');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP_TXT', '不選=僅站長可發');
define('_MA_TADNEWS_DB_UPDATE_ERROR1', '無法更新tad_news_cate資料');
define('_MA_TADNEWS_DB_DEL_ERROR1', '無法刪除tad_news_cate資料');
define('_MA_TADNEWS_CATE_COUNTER', '文章數');
define('_MA_TADNEWS_ONLY_ROOT', '僅管理員');
define('_MA_TADNEWS_CAN_READ_CATE_GROUP_S', '可讀取群組');
define('_MA_TADNEWS_CAN_POST_CATE_GROUP_S', '可管理群組');
define('_MA_TADNEWS_NO', '否（自訂頁面的用法，搭配區塊使用）');
define('_MA_TADNEWS_CATE_PIC', '分類圖片');
define('_MA_TADNEWS_CHANGE_TO_NEWS', '轉為新聞分類');
define('_MA_TADNEWS_CHANGE_TO_PAGE', '轉為自訂頁面分類');

//import.php
define('_MA_TADNEWS_NO_NEWSMOD', '本站沒有安裝 news 新聞模組，故無須進行資料轉移。');
define('_MA_TADNEWS_HAVE_NEWSMOD', '本站安裝的 news 新聞模組版本為 %s 版，共 %s 個分類，%s 篇文章');
define('_MA_TADNEWS_IMPORT_CATE', '請選擇要匯入的文章類別');
define('_MA_TADNEWS_IMPORT', '開始進行文章轉移');

//newspaper.php
define('_MA_TADNEWS_NP', '電子報');
define('_MA_TADNEWS_NP_SELECT', '新增一期電子報');
define('_MA_TADNEWS_NP_MODIFY', '修改電子報頁首頁尾設定');
define('_MA_TADNEWS_NP_DEL', '刪除');
define('_MA_TADNEWS_NP_DEL_DESC', '因有 %s 筆資料故無法刪除');
define('_MA_TADNEWS_NP_OPTION', '選擇一個電子報主題');
define('_MA_TADNEWS_NP_CREATE', '建立新的電子報主題');
define('_MA_TADNEWS_NP_TITLE', '電子報主題名稱');
define('_MA_TADNEWS_NP_DATE', '發佈日期');
define('_MA_TADNEWS_NP_NUMBER', '電子報期數');
define('_MA_TADNEWS_NP_NUMBER_INPUT', '第 %s 期');
define('_MA_TADNEWS_NP_STEP1', '【步驟一】選擇或新建一個電子報主題');
define('_MA_TADNEWS_NP_STEP2', '【步驟二】選擇要放到電子報中的文章');
define('_MA_TADNEWS_NP_STEP3', '【步驟三】編輯電子報');
define('_MA_TADNEWS_NP_STEP4', '【步驟四】寄發電子報');
define('_MA_TADNEWS_NP_CONTENT_HEAD', '電子報標題內容');
define('_MA_TADNEWS_NP_CONTENT_HEAD_DESC', '<p>頁首會出現在電子報最上方，可放圖片或文字。</p>
<p>{N}→「期數」</p>
<p>{T}→「電子報該期標題」</p>
<p>{D}→「發報時間」</p>');
define('_MA_TADNEWS_NP_CONTENT', '電子報內容');
define('_MA_TADNEWS_NP_CONTENT_FOOT', '電子報頁尾內容');
define('_MA_TADNEWS_NP_CONTENT_FOOT_DESC', '若欲回覆系統預設的頁首、頁尾，清空之，按「下一步」即可。');
define('_MA_TADNEWS_NP_TITLE_L', '《');
define('_MA_TADNEWS_NP_TITLE_R', '》');

define('_MA_TADNEWS_NP_HEAD_CONTENT', '<h5 style="color:white;float:right;">%s電子報第 {N} 期</h5><h2>{T}</h1><h2>◎ 訂閱網址：%s ◎ 發報時間：{D}</h2>');

define('_MA_TADNEWS_NP_FOOT_CONTENT', '<div class="foot"><h2>【關於本報】</h1>
<p>◎ 主編：%s</p>
<p>本電子報智慧財產權屬於「<a href="%s" target="_blank">%s</a>」，採用<a href="http://creativecommons.org/licenses/by-sa/2.5/tw/deed.zh_TW" target="_blank">創用 CC「姓名標示－禁止改作－非商業性」授權條款臺灣 2.5 版授權</a>散布。<a href="http://creativecommons.org/licenses/by-sa/2.5/tw/legalcode" target="_blank">《授權條款全文》</a></p>
<p>若欲為上述授權範圍以外之利用，請與「%s」（<a href="mailto:%s">%s</a>） 聯絡。</p>
<p>若欲訂閱或取消本電子報，請至以下網址：<a href="%s" target="_blank">%s</a></p></div>');
define('_MA_TADNEWS_SEND_NOW', '立即寄出');
define('_MA_TADNEWS_MAIL_LIST', '郵寄清單：（共 %s 筆）');
define('_MA_TADNEWS_NP_LIST', '管理現有電子報');
define('_MA_TADNEWS_NP_THEMES', '選擇電子報佈景');
define('_MA_TADNEWS_NP_EMAIL', 'Email管理');
define('_MA_TADNEWS_NP_EMAIL_IMPORT', '匯入Email，請用 , 隔開');
define('_MA_TADNEWS_NEVER_SEND', '尚未寄過');
define('_MA_TADNEWS_SEND_LOG', '觀看寄送紀錄');
define('_MA_TADNEWS_EMPTY_LOG', '查無寄送紀錄');
define('_MA_TADNEWS_BACK_TO', '回「%s」');
define('_MA_TADNEWS_NP_SUB_TITLE', '本期電子報標題：');
define('_MA_TADNEWS_NO_EMAIL', '目前沒有任何電子郵件，可至<a href="newspaper.php?op=newspaper_email&nps_sn=%s">Email管理</a>手動匯入Email。');

//page
define('_MA_TADNEWS_CATE_SHOW_TITLE', '顯示文章標題');
define('_MA_TADNEWS_CATE_SHOW_TOOL', '顯示模組工具');
define('_MA_TADNEWS_CATE_SHOW_COMM', '使用評論功能');
define('_MA_TADNEWS_CATE_SHOW_NAV', '使用上下頁鈕');

//tag.php
define('_MA_TADNEWS_TAG_TITLE', '標籤');
define('_MA_TADNEWS_TAG_FONTCOLOR', '文字顏色');
define('_MA_TADNEWS_TAG_COLOR', '顏色');
define('_MA_TADNEWS_TAG_ENABLE', '是否使用');
define('_MA_TADNEWS_TAG_DEMO', '範例');
define('_MA_TADNEWS_TAG_FUNC', '功能');
define('_MA_TADNEWS_TAG_NEW', '新標籤');
define('_MA_TADNEWS_TAG_ABLE', '啟用');
define('_MA_TADNEWS_TAG_UNABLE', '關閉');
define('_MA_TADNEWS_TAG_AMOUNT', '，有 %s 篇文章使用此標籤');
define('_MA_TADNEWS_NO_PERMISSION', '當沒有讀取權限時');
define('_MA_TADNEWS_HIDE_ARTICLE', '完全隱藏文章');
define('_MA_TADNEWS_DISPLAY_TITLE', '僅顯示標題');
