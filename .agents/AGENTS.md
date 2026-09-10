# tadnews 模組專屬規範

> XOOPS 通用規則（核心物件、安全性、架構鐵則、TadTools、CodeGraph 邊界）
> 由全域 Skill `xoops-core-dev` 提供，此處不重複。

---

## 1. 模組基本資訊

| 項目 | 值 |
|---|---|
| 模組名稱 | `tadnews`|
| 命名空間 | `XoopsModules\Tadnews` |
| 架構書 | `.agents/tadnews_architecture.md` |
| 管理員 Session | 無專屬 key，權限為每次請求即時計算的全域變數 `$tadnews_adm`（`interface.php:18`、`class/Tools.php:207`） |
| 自有資料表 | `tad_news`, `tad_news_cate`, `tad_news_paper`, `tad_news_paper_setup`, `tad_news_paper_email`, `tad_news_paper_send_log`, `tad_news_sign`, `tad_news_tags`, `tadnews_files_center`, `tadnews_rank`, `tadnews_data_center`（共 11 張，見 `xoops_version.php` 的 `$modversion['tables']`，全為 MyISAM / utf8mb4） |
| 外部資料表 | `topics`, `stories`, `newblocks`, `tplfile`, `tplsource`, `tad_news_files`, `tad_themes_menu` — **非本模組所有**，僅 `admin/import.php` 匯入舊 news 模組資料時讀取。改碼時不得新增對這些表的寫入 |

---

## 2. 檔案結構速查

```text
入口分發（無 SQL）：admin/about.php, admin/admin_header.php, admin/import.php, admin/index.php, admin/main.php, admin/menu.php, admin/newspaper.php, admin/page.php, admin/save_cate_sort.php, admin/save_drag.php, admin/tag.php, ajax_list_content.php, ajax_table_content.php, app_api.php, archive.php, backend.php, demo_upload.php, email.php, function.php, index.php, my_news.php, newspaper.php, page.php, post.php, rss.php, save.php, save_sort.php
業務邏輯：class/Constants.php, class/Helper.php, class/TadNewsRest.php, class/Tadnews.php, class/Tools.php, class/Update.php
樣板：templates/blocks/searchbar.tpl, templates/blocks/tadnews_block_cate.tpl, templates/blocks/tadnews_block_cate_news.tpl, templates/blocks/tadnews_block_content_block.tpl, templates/blocks/tadnews_block_covered.tpl, templates/blocks/tadnews_block_focus_news.tpl, templates/blocks/tadnews_block_list_content_block.tpl, templates/blocks/tadnews_block_marquee.tpl, templates/blocks/tadnews_block_my_page.tpl, templates/blocks/tadnews_block_newspaper.tpl, templates/blocks/tadnews_block_newspaper_list.tpl, templates/blocks/tadnews_block_page.tpl, templates/blocks/tadnews_block_page_list.tpl, templates/blocks/tadnews_block_page_menu.tpl, templates/blocks/tadnews_block_slidernews.tpl, templates/blocks/tadnews_block_slidernews2.tpl, templates/blocks/tadnews_block_tab_news.tpl, templates/blocks/tadnews_block_table_content_block.tpl, templates/blocks/tadnews_block_tag_news.tpl, templates/fragment1.tpl, templates/fragment2.tpl, templates/fragment3.tpl, templates/fragment4.tpl, templates/op_archive.tpl, templates/op_list_sign.tpl, templates/op_list_tad_all_pages.tpl, templates/op_list_user_sign.tpl, templates/op_modify_page_cate.tpl, templates/op_show_page.tpl, templates/op_tabs_sort.tpl, templates/op_tad_news_cate_form.tpl, templates/op_tad_news_form.tpl, templates/op_tadnews_cate.tpl, templates/op_tadnews_list.tpl, templates/op_tadnews_show.tpl, templates/op_tadnews_summary.tpl, templates/sub_cate_pic.tpl, templates/sub_rss.tpl, templates/sub_summary.tpl, templates/tadnews_adm_import.tpl, templates/tadnews_adm_main.tpl, templates/tadnews_adm_newspaper.tpl, templates/tadnews_adm_page.tpl, templates/tadnews_adm_tag.tpl, templates/tadnews_index.tpl, templates/tadnews_my_news.tpl, templates/tadnews_newspaper.tpl, templates/tadnews_page.tpl, templates/tadnews_rss.tpl
前端 JS：class/jQuery.Marquee/jquery.marquee.js, class/jquery.upload-1.0.2.min.js, class/slider/jquery.touchslider.min.js
（2026-08-14 已刪：nicEdit.js + nicEditorIcons.gif 改用 tadtools CkEditor；tmt_core.js、tmt_spry_linkedselect.js 為零引用死檔）
語言包：language/english/admin.php, language/english/blocks.php, language/english/global.php, language/english/main.php, language/english/modinfo.php, language/tchinese_utf8/admin.php, language/tchinese_utf8/blocks.php, language/tchinese_utf8/global.php, language/tchinese_utf8/main.php, language/tchinese_utf8/modinfo.php
```

---

## 3. 模組特有注意事項

以下皆為 `xoops-lint` 實測或讀碼確認過的事實，非推測。

**授權模型（動它之前務必先讀）**
- 本模組**沒有** `$_SESSION['tadnews_adm']`。管理權限是每次請求即時計算：`$tadnews_adm = $xoopsUser->isAdmin()`（`interface.php:18`、`class/Tools.php:207`）。
- 一般使用者的發文權限走**分類層級**授權：`Tools::chk_user_cate_power($kind)` 回傳可操作的分類陣列，`$kind` 為 `'post'`（預設）或 `'pass'`（審核）。判斷「有無權限」要看回傳陣列是否非空，不是看布林值。
- 不要為了省一次 `isAdmin()` 呼叫而改成 Session 快取——群組異動後快取不會失效，是拿正確性換效能。

**單表雙用途**
- `tad_news` 同時存「新聞」與「單頁」，靠 `news_kind` 欄位區分（`'news'` / `'page'`）。任何 `tad_news` 的查詢都必須帶 `news_kind` 條件，漏掉會讓單頁混進新聞列表。

**已知技術債（重構優先序見 `.agents/tadnews_architecture.md`）**
- `class/Tadnews.php` 2567 行，採 Fluent Setter 鏈（`set_xxx()->get_news()`）而非參數傳入，`xoops-lint` 在此單檔報 67 條 `NON-STATIC` + 57 條 `RAW-SUPERGLOBAL`。**新功能一律不得再新增 `set_xxx()`**。
- 有 19 個 blocks 呼叫 `class/Tadnews.php`，改動其公開方法前務必用 CodeGraph 查完整呼叫端。
- `function.php` 現在只剩 `$Tadnews = new Tadnews();` 與 autoloader 保險，全域函式與 SQL 已於 2026-08-14 全數搬入 `class/`。檔名留著是因為 6 個入口檔仍 `require_once` 它。
- 入口檔內仍有 8 個全域 view 組裝函式（`index.php` 5、`archive.php`／`my_news.php`／`backend.php` 各 1），皆不含 SQL；`blocks/*.php` 的 19 個區塊主函式是 XOOPS 區塊 API 規定，**不得**搬進 class。

**驗收方式**
- 每次改碼後跑 `php D:/workspace/02_Tools/xoops-lint/xoops-lint.php .`，只要 error 數沒比改碼前多就算過關。

---

## 4. 與官方版的刻意差異（升級時勿被覆蓋）

參照版本：官方 TadNews 5.02（2025-10-30），本機解壓於 `www/www2/modules/tadnews/`，可直接 diff。
以下 4 項是官方 5.02 就存在的 bug，本模組已修；升級覆蓋後要重新套用。

| 檔案 | 差異 | 官方的問題 |
|---|---|---|
| `templates/op_list_tad_all_pages.tpl` | 外層 `foreach` 改 `item=cate`（官方內外層都是 `item=news`） | 內層迴圈蓋掉外層變數 → 頁面連結的 `ncsn` 空掉、迴圈後的判斷全錯 |
| `class/Page.php` `list_tad_all_pages()` | 補 assign `cate_set_tool` | 官方全庫沒人 assign 它，而三個 page 樣板都用它當工具列的 gate → 列表頁工具列永遠不出現 |
| `class/Tadnews.php` `get_news()` | `get_setup()` 回傳非陣列時補預設 `title=1; tool=1` | 分類沒存過 setup 時 `get_setup()` 回傳 `''`，PHP 8 `foreach` 吃字串發 Warning 且什麼都沒 assign |
| `class/Tadnews.php` `get_cate_news()` | subnews 補 `page_sort` | 樣板要顯示排序徽章，但官方只在 `get_news()` 給，列表頁徽章永遠空白 |
| `class/Cate.php` `list_tad_news()` | `ncsn` 沒選分類時 assign `''` 而非 `0` | 樣板一律用 `$ncsn!=""` 判斷有無選分類，PHP 8 起 `0 != ""` 為真 → 後台文章／自訂頁面管理未選分類時仍跑進分類標題區塊，噴 `$cate` 缺 key 的 Warning 並出現破圖 |
| `admin/main.php` | 補 assign `now_op`（比照 `admin/page.php`） | 樣板三處用 `$now_op` 判斷，官方只在 `admin/page.php` assign，`admin/main.php` 漏掉 |
| `class/Tag.php` `list_tad_news_tags()` | 補 assign `enable`，並初始化 `$tagarr` 等區域變數 | 樣板「新標籤」列用 `$enable` 決定預設選項，官方（含 5.02 的 `admin/tag.php`）從沒 assign → PHP 8 每次進標籤管理都噴 4 條 Warning |
| 安全修補（Phase 0/1/2） | 刪 `save.php`；`demo_upload.php` 補權限；`get_news()` keyword 跳脫；`insert/update_tad_news()` 啟用 token 檢查；`post.php` 的 `del_page_tab`／`delete_cover` 補擁有者檢查；`uid_chk` 預設改 1 | 官方 5.02 皆無這些防護，詳見 commit `807ff62`／`76081c8`／Phase 2。**升級後務必重套，這幾項是可被未授權觸發的漏洞** |
| `class/TadNewsRest.php` + `app_api.php` | 修好 `get_cates()` 被截斷的 `$nc_title`、還原被註解掉的 `use ...\Utility`、`app_api.php` 補註冊自身 autoloader、關閉 `$xoopsLogger` | 官方 5.02 的 `TadNewsRest.php` **整檔 parse error**（變數名被換行切成 `$nc_titl` + `e`），且 `app_api.php` 只 require mainfile 不載入 autoloader → App API 任何 op 都是白畫面。另外站台開除錯模式時 logger 的 HTML 會黏在 JSON 後面害 App 端解析失敗，於入口關掉。實測 `?op=get_cates` 回傳純 JSON、`json_decode` 通過 |
| `blocks/tadnews_cate.php:38` | `$counter[$ncsn]` 改用 `empty()` 判斷 | 零文章的分類不在 `GROUP BY ncsn` 結果中，官方直接讀該 key → PHP 8 每個空分類噴一條 `Undefined array key` |
| `my_news.php` `list_tad_my_news()` | 改收 `$kind, $the_ncsn` 參數，刪掉函式內重複的 toolbar assign | 官方在函式內用未定義的 `$kind`／`$the_ncsn`／`$interface_icon`（三個都不在 `global` 清單）→ PHP 8 兩條 Warning，且分類篩選功能實際失效 |
