<?php
//global
require_once __DIR__ . '/global.php';
xoops_loadLanguage('blocks', 'system');
define('_MB_TADNEWS_CONTENT_BLOCK_EDIT_BITEM1', '一橫列放幾則新聞');

define('_MB_TADNEWS_RE_EDIT_BITEM0', '列出回應數');
define('_MB_TADNEWS_RE_EDIT_BITEM1', '秀出回應內容長度');

define('_MB_TADNEWS_CATE_NEWS_EDIT_BITEM0', '選擇要秀出的類別');
define('_MB_TADNEWS_CATE_NEWS_EDIT_BITEM1', '列出文章數');
define('_MB_TADNEWS_CATE_NEWS_EDIT_BITEM2', '是否秀出分類縮圖？');
define('_MB_TADNEWS_CATE_NEWS_EDIT_BITEM3', '是否秀出分隔線？');

define('_MB_TADNEWS_NP_EDIT_BITEM0', '秀出電子報數');
define('_MB_TADNEWS_FOCUS_EDIT_BITEM0', '請選擇要秀出的新聞');
define('_MB_TADNEWS_NO', '否');

define('_MB_TADNEWS_NEWS_TITLE', '文章標題');
define('_MB_TADNEWS_NEWS_CATE', '所屬分類');
define('_MB_TADNEWS_COUNTER', '人氣');
define('_MB_TADNEWS_SHOW_ALL', '全文');

define('_MB_TADNEWS_SUBMIT', '送出');
define('_MB_TADNEWS_TITLE', '電子報：');
define('_MB_TADNEWS_NO_NEWSPAPER', '尚未建立電子報，無法提供訂閱。');
define('_MB_TADNEWS_EMAIL', 'Email ：');
define('_MB_TADNEWS_ORDER', '訂閱');
define('_MB_TADNEWS_CANCEL', '取消');
define('_MB_TADNEWS_ORDER_COUNT', '目前訂閱人數： %s 人');

define('_MB_TADNEWS_NP_TITLE', '第 %s 期');

define('_MB_TADNEWS_PAGE_EDIT_BITEM0', '選擇要秀出哪一個分類的所有文章');
define('_MB_TADNEWS_PAGE_EDIT_BITEM1', '標題長度限制');

//tadnews 1.3.1
define('_MB_TADNEWS_MY_PAGE', '請選擇文章');
define('_MB_TADNEWS_NO_CATE', '不分類');

//tadnews 2.0
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM0', '列出文章數');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1', '摘要字數');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM1_DESC', '（設0，代表不秀出摘要。）');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM2', '摘要CSS設定');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3', '標題字數');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM3_DESC', '（設0，代表完整出現標題。）');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM4', '是否秀出文章縮圖？');
define('_MB_TADNEWS_LIST_CONTENT_BLOCK_EDIT_BITEM5', '縮圖CSS設定');

define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM0', '列出文章數');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM1', '秀出換頁按鈕');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM2', '第一欄');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM3', '第二欄');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM4', '第三欄');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM5', '第四欄');
define('_MB_TADNEWS_TABLE_CONTENT_BLOCK_EDIT_BITEM6', '第五欄');
define('_MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_1', '發佈時間');
define('_MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_2', '文章標題');
define('_MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_3', '發佈者');
define('_MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_4', '所屬分類');
define('_MB_TADNEWS_TABLE_CONTENT_SHOW_CELL_5', '人氣');

define('_MB_TADNEWS_START_FROM', '跳過最新的幾篇文章？');
define('_MB_TADNEWS_HIDE', '不顯示');

define('_MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM0', '顯示區寬度');
define('_MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM1', '顯示區高度');
define('_MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM2', '列出文章數');
define('_MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM3', '文章摘要字數');
define('_MB_TADNEWS_SLIDERNEWS_BLOCK_EDIT_BITEM4', '使用的滑動圖文外掛');

define('_MB_TADNEWS_FOCUS_EDIT_BITEM1', '顯示全文或摘要？');
define('_MB_TADNEWS_FOCUS_FULL', '全文');
define('_MB_TADNEWS_FOCUS_SUMMARY', '摘要');
define('_MB_TADNEWS_LOADING', '新聞載入中，請稍後...');

define('_MB_TADNEWS_MARQUEE_DIRECTION', '方向');
define('_MB_TADNEWS_MARQUEE_DIRECTION_LEFT', '←');
define('_MB_TADNEWS_MARQUEE_DIRECTION_RIGHT', '→');
define('_MB_TADNEWS_MARQUEE_DIRECTION_UP', '↑');
define('_MB_TADNEWS_MARQUEE_DIRECTION_DOWN', '↓');
define('_MB_TADNEWS_MARQUEE_DIRECTION_DESC', '「上下」的速度約 5000 左右；「左右」的速度約 15000 左右（高度也要調整為 height:1em;）');
define('_MB_TADNEWS_MARQUEE_DURATION', '速度（數字越大越慢）');
define('_MB_TADNEWS_MARQUEE_CSS', 'CSS設定（可不設）');
define('_MB_TADNEWS_MARQUEE_CSS_DEFAULT', '目前CSS預設值：');
define('_MB_TADNEWS_MARQUEE_ITEM_CSS', '跑馬燈內容項目的CSS設定');

define('_MB_TADNEWS_LIST_TEMPLATE_NOTE', '（在表格條列模式無作用）');
define('_MB_TADNEWS_LIST_TEMPLATE', '呈現模式');
define('_MB_TADNEWS_LIST_TEMPLATE_LIST', '原條列模式');
define('_MB_TADNEWS_LIST_TEMPLATE_TABLE', '表格條列模式（同模組首頁的條列模式）');

define('_MB_TADNEWS_PAGE_EDIT_BITEM2', '文字大小');

define('_MB_TADNEWS_COVERED_OPT1', '一個橫列放幾篇文章？');
define('_MB_TADNEWS_COVERED_OPT2', '共幾個橫列？');
define('_MB_TADNEWS_AJAX_ERROR', '無法載入資料。');
define('_MB_TADNEWS_PAGE_BG_COLOR', '選擇標題底色');
define('_MB_TADNEWS_PAGE_FONT_COLOR', '選擇標題顏色');
define('_MB_TADNEWS_PAGE_SUB_CATE', '是否列出底下分類？');
define('_MB_TADNEWS_PAGE_BG_CSS', '標題背景樣式');
define('_MB_TADNEWS_PAGE_FONT_CSS', '標題文字樣式');

define('_MB_TADNEWS_TAB_NEWS_DISPLAY_TYPE', '呈現類型');
define('_MB_TADNEWS_TAB_NEWS_DEFAULT', '橫向頁籤');
define('_MB_TADNEWS_TAB_NEWS_VERTICAL', '垂直頁籤');
define('_MB_TADNEWS_TAB_NEWS_ACCORDION', '伸縮選單');

define('_MB_TADNEWS_ACTIVE_BG', '作用中的頁籤底色');
define('_MB_TADNEWS_INACTIV_BG', '未作用的頁籤底色');
define('_MB_TADNEWS_ACTIVE_BORDER_COLOR', '作用中的頁籤上邊框顏色');
define('_MB_TADNEWS_ACTIVE_CONTENT_BORDER_COLOR', '作用中的頁籤內容邊框顏色');
define('_MB_TADNEWS_ADD_ALL_NEWS_TAB', '加入所有最新消息頁籤（不分類）到最前面');
define('_MB_TADNEWS_LATEST_NEWS_TAB', '所有消息');

define('_MB_TADNEWS_PAGE_SHOW_TITLE', '是否顯示分類標題？');

define('_MB_TADNEWS_BAR_CATE', '分類');
define('_MB_TADNEWS_BAR_TAG', '標籤');
define('_MB_TADNEWS_BAR_KEYWORD', '關鍵字');
define('_MB_TADNEWS_BAR_DATE', '日期範圍');
define('_MB_TADNEWS_BAR_START_DAY', '>=此日的公告');
define('_MB_TADNEWS_BAR_END_DAY', '<=此日的公告');

define('_MB_TADNEWS_SEARCHBAR', '是否加入新聞篩選工具？');
define('_MB_TADNEWS_TAB_FONT_SIZE', '頁籤標題文字大小');
