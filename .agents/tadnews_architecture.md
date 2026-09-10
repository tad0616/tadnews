# `tadnews` 模組重構規劃架構書

> 基於《XOOPS 2.5 模組開發規範（dinfo / TadTools 模式）》
> 驗收工具：`xoops-lint`（`D:\workspace\02_Tools\xoops-lint`）
> 通用規範由全域 Skill `xoops-core-dev` 提供，此處不重複。

## 0. 定位：這不是「舊模組改新模組」

tadnews 已具備新模組的骨架，`xoops-lint` 實測以下 error 規則皆為 **0 findings**：

| 規則 | 結果 | 意義 |
|---|---|---|
| `NO-NAMESPACE` | 0 | `class/` 已全數 PSR-4（`preloads/autoloader.php` 已建立） |
| `ECHO-HTML` | 0 | 類方法未輸出 HTML，一律走 `$xoopsTpl->assign()` |
| `DB-DRIVER` | 0 | 未出現 `mysqli_*` / `PDO`，皆走 `$xoopsDB` |
| `LEGACY-MVC` | 0 | 無舊式 Action/Model/Check 分派 |
| `NO-TOKEN` | 0 | 有寫入操作的檔案皆已含 `xoops_security_check()` |

**因此本書規劃的是「補齊落差」，不是從零重建。** 任何「打掉重寫」的提案都應被拒絕——19 個 blocks 依賴 `class/Tadnews.php`，大爆炸重寫的回歸風險遠高於收益。

---

## 1. 專案基本資訊

| 欄位 | 設定值 |
|------|--------|
| **模組名稱** | `tadnews` |
| **目前版本** | 5.0.2-Stable |
| **XOOPS 版本** | 2.5.10+（`$modversion['min_xoops']`） |
| **PHP 版本** | 目前宣告 `min_php` 7.3；Phase 5 後可評估提升至 8.x |
| **MySQL** | MyISAM / utf8mb4（11 張自有表全數） |
| **核心依賴** | `tadtools`（Utility, SweetAlert, FormValidator, CkEditor, StarRating） |
| **命名空間** | `XoopsModules\Tadnews\`（PSR-4，已完成） |

---

## 2. 資料庫設計（現況）

### 2.1 自有表（11 張，`$modversion['tables']`）

| 資料表 | 用途 |
|--------|------|
| `tad_news` | **新聞與單頁共用主表**，靠 `news_kind`（`news` / `page`）區分 |
| `tad_news_cate` | 樹狀分類（`of_ncsn` 指向父分類），亦承載分類層級授權設定 |
| `tadnews_files_center` | 附件檔案中心 |
| `tad_news_paper` / `_setup` / `_email` / `_send_log` | 電子報內容、發送設定、訂閱名單、寄送紀錄 |
| `tad_news_sign` | 使用者簽收 / 已讀紀錄 |
| `tad_news_tags` | 標籤 |
| `tadnews_rank` | 點閱排行統計 |
| `tadnews_data_center` | 前台自訂欄位資料 |

### 2.2 外部表（唯讀，非本模組所有）

`topics`, `stories`, `newblocks`, `tplfile`, `tplsource`, `tad_news_files`, `tad_themes_menu`

`topics` / `stories` 由 **`class/Importer.php`**（舊 news 模組匯入）唯讀存取；`tad_themes_menu` 由 `class/Cate.php`、`class/Page.php` 存取；其餘由 `class/Update.php`（版本升級）處理。屬一次性遷移路徑。**重構時不得新增對這些表的寫入，且不得留在入口檔。**

---

## 3. 授權模型（改碼前必讀）

```text
管理員判斷    $tadnews_adm = $xoopsUser->isAdmin()   ← 每次請求即時計算，無 Session 快取
              (interface.php:18、class/Tools.php:207)

一般使用者    Tools::chk_user_cate_power($kind)      ← 回傳「可操作的分類陣列」
              $kind = 'post'（發文，預設）| 'pass'（審核）
              判斷有無權限 = 陣列是否非空，不是布林值
```

**設計決策：維持即時計算，不改為 `$_SESSION['tadnews_adm']`。** 快取可省一次 `isAdmin()`，但群組異動後不會失效，是拿正確性換微不足道的效能。

---

## 4. 請求生命週期（現況，⚠️ 標註違規點）

```text
[瀏覽器請求]
   ↓
[入口檔 index.php / post.php / admin/main.php …]
   1. require header.php → mainfile.php + function.php(⚠️) + interface.php
   2. Xmf\Request 取參數
   3. switch($op) 分派
   4. ⚠️ ENTRY-SQL：17 個入口檔直接組 SQL，共 130 條違規
   ↓
[class/Tadnews.php  2567 行]
   ⚠️ Fluent Setter 鏈：$Tadnews->set_show_num()->set_news_kind()->get_news('assign')
      呼叫端把狀態一路堆進物件，無法從單一呼叫看出實際查詢條件
      → 這正是它膨脹到 2567 行的成因（lint：67 NON-STATIC + 57 RAW-SUPERGLOBAL）
   ↓
[Smarty 樣板 templates/*.tpl]
   已整合 TadTools（SweetAlert 二次確認、FormValidator）
```

---

## 5. 違規基線

| 時點 | errors | warnings | 檔數 |
|---|---:|---:|---:|
| 起始基線 | 172 | 212 | 127 |
| Phase 0–3 完成後 | 133 | 210 | 124 |
| Phase 4 試點（`admin/newspaper.php`） | 96 | 210 | 125 |
| Phase 4 第 2 檔（`admin/admin_function.php`） | 80 | 211 | 125 |
| Phase 4 第 3 檔（`admin/tag.php`） | 70 | 211 | 126 |
| Phase 4 第 4 檔（`function.php`） | 60 | 211 | 126 |
| Phase 4 第 5 檔（`page.php`） | 50 | 211 | 127 |
| Phase 4 第 6 檔（`admin/import.php`） | 41 | 211 | 128 |
| Phase 4 第 7 檔（`index.php`） | 35 | 211 | 129 |
| Phase 4 第 8 檔（`save_sort.php`） | 29 | 211 | 129 |
| Phase 4 第 9–10 檔（`admin/page.php`+`admin/main.php`） | 22 | 211 | 129 |
| **Phase 4 完成（現況）** | **3** | **209** | **128** |

**`ENTRY-SQL` 已歸零。** 現存 3 條 errors 全為 `RAW-FILES`，集中在 `class/Cate.php`（124／182／392 行），即 Phase 3b 的分類封面圖議題。

新建的類別：`Paper`(25)、`Cate`(17)、`Page`(7)、`Tag`(6)、`Importer`(5)、`Sign`(3)，方法皆為 `public static`。

### 起始基線明細（2026-08-14 實測）

### Error

| 規則 | 數量 | 集中處 |
|---|---:|---|
| `ENTRY-SQL` | **130** | `admin/newspaper.php` 37、`admin/admin_function.php` 16、`admin/tag.php` 10、`function.php` 10、`page.php` 10、`admin/import.php` 9、其餘 11 檔 38 |
| `SQL-NOCHECK` | 20 | **全在 `class/Update.php` 單檔** |
| `VAR-VAR` | 9 | `class/Tadnews.php` 5、`admin/newspaper.php` 2、`ajax_table_content.php` 1、`mk_rss.php` 1 |
| `NATIVE-DIALOG` | 7 | `class/tmt_spry_linkedselect.js` 4、`class/nicEdit.js` 3 |
| `RAW-FILES` | 6 | `admin/admin_function.php` 2、`class/Tadnews.php` 2、`demo_upload.php` 1、`function.php` 1 |

### Warn（error 歸零後再處理）

| 規則 | 數量 | 集中處 |
|---|---:|---|
| `RAW-SUPERGLOBAL` | 100 | `class/Tadnews.php` 57 |
| `NON-STATIC` | 78 | `class/Tadnews.php` 67 |
| `HEADING` | 26 | 15 個 tpl |
| `HARDCODED-ZH` | 6 | 4 檔 |
| `IMG-SIZE` | 1 | `templates/sub_rss.tpl` |

---

## 5b. 驗收工具（每次改碼後都要跑）

| 工具 | 抓什麼 | 指令 |
|---|---|---|
| `php -l` | 語法錯誤 | `php -l <檔>` |
| **xoops-lint** | 規範違規（ENTRY-SQL、VAR-VAR…） | `php D:/workspace/02_Tools/xoops-lint/xoops-lint.php .` |
| **xoops-refactor-verify** | **搬移造成的執行期破壞** | `php D:/workspace/02_Tools/xoops-refactor-verify/verify.php` |

第三項是 Phase 4 結束後補建的，因為前兩項有共同盲區 —— 以下兩類破壞它們都看不到，只會在使用者點下去時炸掉：

1. **搬走函式後漏改某個呼叫端** → 呼叫不存在的函式，fatal。
2. **搬進方法後漏宣告 `global $xoopsDB`** → 不報錯，變數變成 `null`，SQL 靜默失效。這類最陰險。

> 該工具以 `token_get_all()` 解析。初版用 regex，把 SQL 字串裡的 `AND (`、heredoc 裡的 JS `getElementById()`、註解掉的 `//$xoopsTpl->assign()` 全當成真實呼叫，噴出數十條誤判。**會亂叫的檢查比沒有檢查更糟。**

> 工具本身也做過注入故障的反向測試，確認抓得到問題 —— 一個永遠通過的檢查等於沒有檢查。

**Phase 4 結束時的實測結果：A、B 兩項皆為 0。**

### 仍未驗證的部分（重要）

以上全是**靜態**檢查，驗證的是結構不是行為。**至今未做過任何執行期驗證。** 以下風險仍在：

- 樣板變數：方法少 `assign()` 一個變數，或樣板取用了沒 assign 的變數 —— 靜態工具看不到。
- 實際的 CRUD 流程、權限判斷、電子報寄送、檔案上傳是否仍正常。

**進入 Phase 5 前，應先以瀏覽器實際走過一輪主要流程**（前台列表／單篇／單頁、後台分類 CRUD、標籤、電子報建立與預覽、匯入頁）。目前程式碼已在 git 保護下（首次 commit：入口檔 SQL 全數移入 class/），出問題可比對還原。

---

## 6. 開發鐵則

1. **狀態傳遞**：新方法一律「參數傳入」，**不得再新增任何 `set_xxx()`**。既有 Fluent 方法逐步汰換，不強制一次重寫。
2. **雙層過濾**：入口用 `Xmf\Request`，Class 內用 `Tools::filter()`（已符合，維持）。
3. **SQL 錯誤處理**：`query()` / `queryF()` 後必接 `or Utility::web_error($sql, __FILE__, __LINE__)`。
4. **TadTools 強制整合**：刪除確認用 `SweetAlert`（禁原生 `confirm()`）、表單驗證用 `FormValidator`、上傳用 `TadUpFiles`（禁自行處理 `$_FILES`）。
5. **禁止事項**：❌ 入口檔寫 SQL ・ ❌ Class 中 `echo` HTML ・ ❌ 可變變數 `$$key` ・ ❌ 硬編碼中文（放 `language/`）・ ❌ 寫入型 op 後忘記 `header("location: …"); exit;`
6. **改動 `class/Tadnews.php` 公開方法前**，先用 CodeGraph 查呼叫端（19 個 blocks 依賴它）。

---

## 7. Roadmap（依 lint error 數與風險排序）

排序原則：**先做單檔、可刪除、零呼叫端影響的，再啃佔 76% 的主線。**

### Phase 0 — 基線校正 ✅ 已完成
- 填畢 `.agents/AGENTS.md` 三處「待確認」，建立本架構書。
- 出場條件：`AGENTS-TODO` = 0、`AGENTS-DEADPATH` = 0。

### Phase 1 — `SQL-NOCHECK` 20 → 0
- 全部在 `class/Update.php` 一個檔，機械性補 `or Utility::web_error()`。
- 單檔、零呼叫端影響，CP 值最高。

### Phase 2 — `NATIVE-DIALOG` 7 → 0
- `class/tmt_core.js`、`class/tmt_spry_linkedselect.js`：**全模組零引用，死檔，直接刪**（−4）。
- `class/nicEdit.js`：僅 `admin/newspaper.php:224` 一處引用，模組已在用 CkEditor，換掉後刪檔（−3）。

### Phase 3 — `VAR-VAR` 9 → 0 ✅、`RAW-FILES` 6 → 3 ⚠️
- ✅ `$$key` 全數改為明確賦值。`SELECT *` 的站點以完整 schema 欄位清單逐一賦值，**刻意保留變數洩漏出迴圈的原行為**（PHP 為函式作用域，`get_news()` line 577 的 `$cate_setup[$ncsn]` 依賴最後一列的 `$ncsn`）。`ajax_table_content.php` 的動態欄位改用 map，與同檔表頭既有的 `$tt[$colname]` 寫法一致。
- ✅ 3 條 `RAW-FILES` 確認為誤判，已加 `xoops-lint-ignore` 並註明理由：`class/Tadnews.php` 2 處（存在性檢查 / 取副檔名，實際上傳走 `TadUpFiles->upload_file()`）、`demo_upload.php` 1 處（`$_FILES` 僅作參數傳給 `TadUpFiles->upload_one_file()`）。
- ⚠️ **剩餘 3 條為真實違規，但被本路線圖錯估規模**，需獨立成 Phase：見下方 Phase 3b。

### Phase 3b — 分類封面圖改走 TadUpFiles（**儲存模型遷移，非局部改動**）
根因單一：`admin/admin_function.php:114` 的 `mk_thumb()` 直接使用 `\Verot\Upload\Upload`（tadtools 內附的第三方庫），而非 `TadUpFiles`。另 2 條（`admin/admin_function.php:172`、`function.php:182`）是呼叫它的 `if (!empty($_FILES['cate_pic']))` 守衛。

之所以不能在 Phase 3 順手修完：兩者**儲存模型不同**。
- 現況：檔案存為 `/uploads/tadnews/cate/{ncsn}.png`，`tad_news_cate.cate_pic` 欄位存 `"{ncsn}.png"`，樣板與區塊直接組該路徑。
- `TadUpFiles`：存入 `tadnews_files_center` 表，檔名為雜湊值，取用需走 files_center API。

因此改造牽涉：儲存路徑、`cate_pic` 欄位語意、所有讀取 `cate_pic` 的樣板／區塊，**以及既有站台分類圖的資料遷移腳本**。應排在 Phase 4（ENTRY-SQL）之後、與 Phase 5 拆分一併評估，或明確決定「維持現況並永久標註 ignore」。

### Phase 4 — `ENTRY-SQL` 130 → 0（主線，佔全部 error 的 76%）

#### ✅ 試點完成：`admin/newspaper.php` 37 → 0

569 行的全域函式區搬入新建的 `class/Paper.php`（19 個 `public static` 方法），入口檔只留 118 行路由。**行為未變更**。可複製的步驟：

1. **先查跨檔呼叫端**：`grep -rln "\bfn_name\b" --include=*.php .`。本檔 17 個函式**全為檔案內部使用**（`.tpl` 的命中都是 URL 的 `op=` 字串；`email.php` 有自己的 `check_email_mx()` 同名定義），故不需保留包裝函式。**其他檔不保證如此，務必逐一確認。**
2. **檢查搬進 namespace 後會斷掉的參照**：全域**函式**呼叫會自動 fallback 到全域空間，安全；全域**類別**不會，必須加 `\` 前綴。掃描方式：`grep -oE "new [A-Za-z_\\\\][A-Za-z0-9_\\\\]*|[A-Za-z_\\\\][A-Za-z0-9_\\\\]*::"`。本檔全部已 import 或已有 `\`（`\MyTextSanitizer`）。
3. **確認無 `echo` / `print`**，否則搬進 `class/` 會觸發 `ECHO-HTML`。本檔全走 `$xoopsTpl->assign()`。
4. **用腳本做機械轉換**，不要手打：`^function x` → `public static function x`、整區縮排 4 格、函式間互相呼叫改 `self::`。腳本應在行數或預期字串對不上時 **abort**，不要硬改。
5. **入口檔改 `Paper::fn()`**：取代時長名優先排序，否則 `save_newspaper` 會誤吃 `save_newspaper_set`。
6. **清掉入口檔用不到的 `use`**（搬移後 `Tools`/`CkEditor`/`SweetAlert`/`Tmt`/`Utility` 都只剩 class 在用）。
7. **驗收**：`php -l` 兩個檔 + 以 Reflection 確認入口的每個 `Paper::x()` 都對應到實際的 public static 方法 + `xlint .` 比對 error 數。

> PSR-4 自動載入無需註冊：`preloads/autoloader.php` 直接把 `XoopsModules\Tadnews\Paper` 對映到 `class/Paper.php`。

#### ✅ 第 2 檔完成：`admin/admin_function.php` 16 → 0（整檔刪除）

10 個全域函式搬入新建的 `class/Cate.php`（`public static`）。與試點的差別：**這次有跨檔呼叫端**（`admin/main.php` 7 處、`admin/page.php` 7 處、`function.php` 1 處），全部直接改為 `Cate::`，未留包裝函式。搬完後原檔只剩 use 敘述，已整檔刪除，並移除 5 個入口檔的 `require_once admin_function.php`。

順帶修掉一個潛在地雷：`function.php:183` 原本呼叫 `mk_thumb()`，但 `function.php` 會被前台入口載入、`admin_function.php` 不會 —— 前台若走到該分支就是 fatal error。改走自動載入的 `Cate::mk_thumb()` 後不再有這個順序依賴。

> ⚠️ **Phase 3b 位置變更**：`mk_thumb()` 的 2 條 `RAW-FILES` 隨函式搬到 `class/Cate.php:122,180`，總數不變。

#### ✅ 第 3 檔完成：`admin/tag.php` 10 → 0

6 個函式搬入 `class/Tag.php`，入口從 144 行縮到 47 行（純路由）。無跨檔呼叫端，與試點同樣單純。

#### ✅ 第 4 檔完成：`function.php` 10 → 0

5 個函式**分拆到兩個既有類別**：`get_newspaper_set` / `get_newspaper` / `preview_newspaper` → `Paper.php`；`tad_news_cate_form` / `update_tad_news_cate` → `Cate.php`。外部呼叫端 10 處（`email.php`、`newspaper.php`、`admin/newspaper.php`、`admin/main.php`、`admin/page.php`、`page.php`）。

**`function.php` 不可刪除**：它除了函式外還有 `$Tadnews = new Tadnews();` 這個全域物件初始化，被全模組 `global $Tadnews` 依賴。搬走函式後保留 13 行的 bootstrap。

#### ✅ 第 5 檔完成：`page.php` 10 → 0（新建 `class/Page.php`）

4 個函式 + **switch `default` 分支裡的行內 SQL** 一併搬入。行內 SQL 收成 `Page::get_title($nsn)`（取單頁標題供麵包屑用）—— 這是前幾檔沒遇過的形態：**`ENTRY-SQL` 不一定都在函式裡，也可能直接寫在路由分支中**，抽取腳本要同時處理。

**歸屬決策**：原規劃把 `page.php` 併入 `Cate.php`，實作時改為新建 `class/Page.php`。理由：這 4 個函式是**單頁（`tad_news.news_kind='page'`）的顯示與列表**，不是分類 CRUD；放進名為「分類管理」的類別會誤導後續維護者。`admin/page.php`（5 條）之後也歸此類別。

#### ✅ 第 6 檔完成：`admin/import.php` 9 → 0（新建 `class/Importer.php`）

5 個函式搬入，入口從 157 行縮到 38 行。

**歸屬決策**：原規劃併入 `class/Update.php`，實作時改為新建 `class/Importer.php`。理由：`Update.php` 是**模組自身的版本升級**（`chk_chkN`/`go_updateN` 探針與 ALTER），`import.php` 是**從另一個模組（舊 news）匯入資料**的一次性遷移工具 —— 兩者生命週期與觸發時機都不同，混在一起會讓 `Update.php` 變成雜物櫃。外部表（`topics`、`stories`）的唯讀存取因此集中在 `Importer.php`，已於類別 docblock 註明。

**流程改進**：本檔起將「抽取」與「rewire 呼叫端」合併為**單一腳本、最後才寫檔**，徹底消除第 3 檔記錄的破窗期。後續各檔沿用此作法。

#### 🔴 最重要的教訓：搬移會回頭打破**已經搬過**的類別

把 `get_newspaper_set()` 等搬進 `Paper.php` 時，`Paper.php` 裡**先前搬入**的方法仍以裸全域函式呼叫它們（11 處）。那些全域函式此刻已不存在 → 執行期 fatal。抽取腳本只轉換了「新附加的區塊」，沒回頭處理既有內容。

更糟的是驗收腳本當時**掃不到**：它只 glob `*.php` 與 `admin/*.php`，漏了 `class/`。是補掃 `class/` 後才浮現。

**因此驗收腳本必須：**
- 掃描範圍涵蓋 `*.php`、`admin/*.php`、**`class/*.php`**、`blocks/*.php`
- 比對時用 `(?<!function )` 排除定義行，避免把方法定義誤判成呼叫
- 每搬完一個函式，就把它加進「舊全域函式名稱」清單永久保留 —— 這份清單是防止後續搬移互相破壞的唯一保險

#### ⚠️ 腳本撰寫的兩個教訓（第 3 檔踩到）
1. **邊界要用內容斷言，不要只算行數**。第一次抽取算錯一行（把 `/*---function區---*/` 當成首個函式的註解），是腳本裡的 `strpos($all[$i], '編輯表單')` 守衛擋下來的。**每個 slice 邊界都要配一個內容斷言。**
2. **抽取與 rewire 之間有破窗期**。抽取腳本截斷了入口檔後、rewire 腳本因 CRLF 比對失敗而中止，那段期間入口檔呼叫的是已不存在的全域函式。字串比對一律要能容忍 `\r\n`（逐行 regex，不要整塊 `strpos`），且**腳本必須在最後才寫檔**，中止時不留半套。

#### ⚠️ 工具環境陷阱（會重複踩到）
在 Git Bash 下用 `php -r '...'` 執行含**反斜線命名空間**的程式碼時，MSYS 的路徑轉換會把類別名弄壞，造成 `class_exists()` 假性失敗。**驗證腳本一律寫成 `.php` 檔再執行**，不要用 `php -r` 內嵌。

#### ✅ 第 7 檔完成：`index.php` 6 → 0（新建 `class/Sign.php`）

**只搬有 SQL 的部分**：3 個簽收函式（`have_read`／`list_sign`／`list_user_sign`，皆操作 `tad_news_sign`）搬入新建的 `class/Sign.php`；`chk_always_top()` 是新聞置頂維護、不屬簽收，併入 `class/Tadnews.php` 作為 `public static`。

**刻意不搬的部分**：另外 5 個函式（`list_tad_summary_news`／`list_tad_all_news`／`list_tad_tag_news`／`list_tad_cate_news`／`show_news`）**完全沒有 SQL**，只是對 `$Tadnews` 物件的編排。鐵則禁的是「入口檔寫 SQL」，不是「入口檔不得有函式」——不搬不需要搬的東西，維持最小差異。

跨檔呼叫端：`my_news.php:36` 的 `have_read()`（`grep` 時注意 `show_news` 在 `app_api.php`／`TadNewsRest.php` 的命中是**同名的物件方法**，不是這個全域函式）。

#### ✅ 第 8 檔完成：`save_sort.php` 6 → 0

AJAX 端點，**無函式、SQL 全部行內**。兩個分支各收成 `Page::save_tabs_sort()` / `Page::save_page_sort()`，入口從 101 行縮到 18 行。注意與既有的 `Page::tabs_sort()` 區分：後者是排序**畫面**，前者是排序**存檔**。

> 命名空間內，未加前綴的**常數**（`_TAD_SORT_FAIL`、`_TAD_SORTED`）與**函式**一樣會 fallback 到全域空間，搬進 class 後不需改。只有**類別**不會 fallback。

#### ✅ 第 9–10 檔完成：`admin/page.php` 5 + `admin/main.php` 2 → 0（合併重複實作）

兩檔各有一個**同名但不同實作**的 `list_tadnews_cate_tree()`（各自獨立載入，故未衝突）。差異僅四處：`not_news` 篩選、連結目標、ztree 名稱、以及 **`admin/main.php` 有 `addslashes($nc_title)` 而 `admin/page.php` 沒有**。

合併為單一 `Cate::cate_tree($def_ncsn, $not_news, $page, $tree_name)`，統一採用**有 `addslashes` 的版本** —— 這順帶修掉 `admin/page.php` 的潛在缺陷：分類標題含單引號時會破壞 ztree 的 JS 字串。屬刻意的行為變更，非純搬移。

#### 🚨 事故：腳本把兩個入口檔寫成空檔

清理懸空註解時，regex 編譯失敗使 `preg_replace()` 回傳 `null`，腳本未檢查就 `file_put_contents(.agents/tadnews_architecture.md, # `tadnews` 模組重構規劃架構書

> 基於《XOOPS 2.5 模組開發規範（dinfo / TadTools 模式）》
> 驗收工具：`xoops-lint`（`D:\workspace\02_Tools\xoops-lint`）
> 通用規範由全域 Skill `xoops-core-dev` 提供，此處不重複。

## 0. 定位：這不是「舊模組改新模組」

tadnews 已具備新模組的骨架，`xoops-lint` 實測以下 error 規則皆為 **0 findings**：

| 規則 | 結果 | 意義 |
|---|---|---|
| `NO-NAMESPACE` | 0 | `class/` 已全數 PSR-4（`preloads/autoloader.php` 已建立） |
| `ECHO-HTML` | 0 | 類方法未輸出 HTML，一律走 `$xoopsTpl->assign()` |
| `DB-DRIVER` | 0 | 未出現 `mysqli_*` / `PDO`，皆走 `$xoopsDB` |
| `LEGACY-MVC` | 0 | 無舊式 Action/Model/Check 分派 |
| `NO-TOKEN` | 0 | 有寫入操作的檔案皆已含 `xoops_security_check()` |

**因此本書規劃的是「補齊落差」，不是從零重建。** 任何「打掉重寫」的提案都應被拒絕——19 個 blocks 依賴 `class/Tadnews.php`，大爆炸重寫的回歸風險遠高於收益。

---

## 1. 專案基本資訊

| 欄位 | 設定值 |
|------|--------|
| **模組名稱** | `tadnews` |
| **目前版本** | 5.0.2-Stable |
| **XOOPS 版本** | 2.5.10+（`$modversion['min_xoops']`） |
| **PHP 版本** | 目前宣告 `min_php` 7.3；Phase 5 後可評估提升至 8.x |
| **MySQL** | MyISAM / utf8mb4（11 張自有表全數） |
| **核心依賴** | `tadtools`（Utility, SweetAlert, FormValidator, CkEditor, StarRating） |
| **命名空間** | `XoopsModules\Tadnews\`（PSR-4，已完成） |

---

## 2. 資料庫設計（現況）

### 2.1 自有表（11 張，`$modversion['tables']`）

| 資料表 | 用途 |
|--------|------|
| `tad_news` | **新聞與單頁共用主表**，靠 `news_kind`（`news` / `page`）區分 |
| `tad_news_cate` | 樹狀分類（`of_ncsn` 指向父分類），亦承載分類層級授權設定 |
| `tadnews_files_center` | 附件檔案中心 |
| `tad_news_paper` / `_setup` / `_email` / `_send_log` | 電子報內容、發送設定、訂閱名單、寄送紀錄 |
| `tad_news_sign` | 使用者簽收 / 已讀紀錄 |
| `tad_news_tags` | 標籤 |
| `tadnews_rank` | 點閱排行統計 |
| `tadnews_data_center` | 前台自訂欄位資料 |

### 2.2 外部表（唯讀，非本模組所有）

`topics`, `stories`, `newblocks`, `tplfile`, `tplsource`, `tad_news_files`, `tad_themes_menu`

`topics` / `stories` 由 **`class/Importer.php`**（舊 news 模組匯入）唯讀存取；`tad_themes_menu` 由 `class/Cate.php`、`class/Page.php` 存取；其餘由 `class/Update.php`（版本升級）處理。屬一次性遷移路徑。**重構時不得新增對這些表的寫入，且不得留在入口檔。**

---

## 3. 授權模型（改碼前必讀）

```text
管理員判斷    $tadnews_adm = $xoopsUser->isAdmin()   ← 每次請求即時計算，無 Session 快取
              (interface.php:18、class/Tools.php:207)

一般使用者    Tools::chk_user_cate_power($kind)      ← 回傳「可操作的分類陣列」
              $kind = 'post'（發文，預設）| 'pass'（審核）
              判斷有無權限 = 陣列是否非空，不是布林值
```

**設計決策：維持即時計算，不改為 `$_SESSION['tadnews_adm']`。** 快取可省一次 `isAdmin()`，但群組異動後不會失效，是拿正確性換微不足道的效能。

---

## 4. 請求生命週期（現況，⚠️ 標註違規點）

```text
[瀏覽器請求]
   ↓
[入口檔 index.php / post.php / admin/main.php …]
   1. require header.php → mainfile.php + function.php(⚠️) + interface.php
   2. Xmf\Request 取參數
   3. switch($op) 分派
   4. ⚠️ ENTRY-SQL：17 個入口檔直接組 SQL，共 130 條違規
   ↓
[class/Tadnews.php  2567 行]
   ⚠️ Fluent Setter 鏈：$Tadnews->set_show_num()->set_news_kind()->get_news('assign')
      呼叫端把狀態一路堆進物件，無法從單一呼叫看出實際查詢條件
      → 這正是它膨脹到 2567 行的成因（lint：67 NON-STATIC + 57 RAW-SUPERGLOBAL）
   ↓
[Smarty 樣板 templates/*.tpl]
   已整合 TadTools（SweetAlert 二次確認、FormValidator）
```

---

## 5. 違規基線

| 時點 | errors | warnings | 檔數 |
|---|---:|---:|---:|
| 起始基線 | 172 | 212 | 127 |
| Phase 0–3 完成後 | 133 | 210 | 124 |
| Phase 4 試點（`admin/newspaper.php`） | 96 | 210 | 125 |
| Phase 4 第 2 檔（`admin/admin_function.php`） | 80 | 211 | 125 |
| Phase 4 第 3 檔（`admin/tag.php`） | 70 | 211 | 126 |
| Phase 4 第 4 檔（`function.php`） | 60 | 211 | 126 |
| Phase 4 第 5 檔（`page.php`） | 50 | 211 | 127 |
| Phase 4 第 6 檔（`admin/import.php`） | 41 | 211 | 128 |
| **Phase 4 第 7 檔（`index.php`，現況）** | **35** | **211** | **129** |

現存 35 條 errors = `ENTRY-SQL` 32 + `RAW-FILES` 3（**全數集中在 `class/Cate.php`**，見 Phase 3b）。

### 起始基線明細（2026-08-14 實測）

### Error

| 規則 | 數量 | 集中處 |
|---|---:|---|
| `ENTRY-SQL` | **130** | `admin/newspaper.php` 37、`admin/admin_function.php` 16、`admin/tag.php` 10、`function.php` 10、`page.php` 10、`admin/import.php` 9、其餘 11 檔 38 |
| `SQL-NOCHECK` | 20 | **全在 `class/Update.php` 單檔** |
| `VAR-VAR` | 9 | `class/Tadnews.php` 5、`admin/newspaper.php` 2、`ajax_table_content.php` 1、`mk_rss.php` 1 |
| `NATIVE-DIALOG` | 7 | `class/tmt_spry_linkedselect.js` 4、`class/nicEdit.js` 3 |
| `RAW-FILES` | 6 | `admin/admin_function.php` 2、`class/Tadnews.php` 2、`demo_upload.php` 1、`function.php` 1 |

### Warn（error 歸零後再處理）

| 規則 | 數量 | 集中處 |
|---|---:|---|
| `RAW-SUPERGLOBAL` | 100 | `class/Tadnews.php` 57 |
| `NON-STATIC` | 78 | `class/Tadnews.php` 67 |
| `HEADING` | 26 | 15 個 tpl |
| `HARDCODED-ZH` | 6 | 4 檔 |
| `IMG-SIZE` | 1 | `templates/sub_rss.tpl` |

---

## 5b. 驗收工具（每次改碼後都要跑）

| 工具 | 抓什麼 | 指令 |
|---|---|---|
| `php -l` | 語法錯誤 | `php -l <檔>` |
| **xoops-lint** | 規範違規（ENTRY-SQL、VAR-VAR…） | `php D:/workspace/02_Tools/xoops-lint/xoops-lint.php .` |
| **xoops-refactor-verify** | **搬移造成的執行期破壞** | `php D:/workspace/02_Tools/xoops-refactor-verify/verify.php` |

第三項是 Phase 4 結束後補建的，因為前兩項有共同盲區 —— 以下兩類破壞它們都看不到，只會在使用者點下去時炸掉：

1. **搬走函式後漏改某個呼叫端** → 呼叫不存在的函式，fatal。
2. **搬進方法後漏宣告 `global $xoopsDB`** → 不報錯，變數變成 `null`，SQL 靜默失效。這類最陰險。

> 該工具以 `token_get_all()` 解析。初版用 regex，把 SQL 字串裡的 `AND (`、heredoc 裡的 JS `getElementById()`、註解掉的 `//$xoopsTpl->assign()` 全當成真實呼叫，噴出數十條誤判。**會亂叫的檢查比沒有檢查更糟。**

> 工具本身也做過注入故障的反向測試，確認抓得到問題 —— 一個永遠通過的檢查等於沒有檢查。

**Phase 4 結束時的實測結果：A、B 兩項皆為 0。**

### 仍未驗證的部分（重要）

以上全是**靜態**檢查，驗證的是結構不是行為。**至今未做過任何執行期驗證。** 以下風險仍在：

- 樣板變數：方法少 `assign()` 一個變數，或樣板取用了沒 assign 的變數 —— 靜態工具看不到。
- 實際的 CRUD 流程、權限判斷、電子報寄送、檔案上傳是否仍正常。

**進入 Phase 5 前，應先以瀏覽器實際走過一輪主要流程**（前台列表／單篇／單頁、後台分類 CRUD、標籤、電子報建立與預覽、匯入頁）。目前程式碼已在 git 保護下（首次 commit：入口檔 SQL 全數移入 class/），出問題可比對還原。

---

## 6. 開發鐵則

1. **狀態傳遞**：新方法一律「參數傳入」，**不得再新增任何 `set_xxx()`**。既有 Fluent 方法逐步汰換，不強制一次重寫。
2. **雙層過濾**：入口用 `Xmf\Request`，Class 內用 `Tools::filter()`（已符合，維持）。
3. **SQL 錯誤處理**：`query()` / `queryF()` 後必接 `or Utility::web_error($sql, __FILE__, __LINE__)`。
4. **TadTools 強制整合**：刪除確認用 `SweetAlert`（禁原生 `confirm()`）、表單驗證用 `FormValidator`、上傳用 `TadUpFiles`（禁自行處理 `$_FILES`）。
5. **禁止事項**：❌ 入口檔寫 SQL ・ ❌ Class 中 `echo` HTML ・ ❌ 可變變數 `$$key` ・ ❌ 硬編碼中文（放 `language/`）・ ❌ 寫入型 op 後忘記 `header("location: …"); exit;`
6. **改動 `class/Tadnews.php` 公開方法前**，先用 CodeGraph 查呼叫端（19 個 blocks 依賴它）。

---

## 7. Roadmap（依 lint error 數與風險排序）

排序原則：**先做單檔、可刪除、零呼叫端影響的，再啃佔 76% 的主線。**

### Phase 0 — 基線校正 ✅ 已完成
- 填畢 `.agents/AGENTS.md` 三處「待確認」，建立本架構書。
- 出場條件：`AGENTS-TODO` = 0、`AGENTS-DEADPATH` = 0。

### Phase 1 — `SQL-NOCHECK` 20 → 0
- 全部在 `class/Update.php` 一個檔，機械性補 `or Utility::web_error()`。
- 單檔、零呼叫端影響，CP 值最高。

### Phase 2 — `NATIVE-DIALOG` 7 → 0
- `class/tmt_core.js`、`class/tmt_spry_linkedselect.js`：**全模組零引用，死檔，直接刪**（−4）。
- `class/nicEdit.js`：僅 `admin/newspaper.php:224` 一處引用，模組已在用 CkEditor，換掉後刪檔（−3）。

### Phase 3 — `VAR-VAR` 9 → 0 ✅、`RAW-FILES` 6 → 3 ⚠️
- ✅ `$$key` 全數改為明確賦值。`SELECT *` 的站點以完整 schema 欄位清單逐一賦值，**刻意保留變數洩漏出迴圈的原行為**（PHP 為函式作用域，`get_news()` line 577 的 `$cate_setup[$ncsn]` 依賴最後一列的 `$ncsn`）。`ajax_table_content.php` 的動態欄位改用 map，與同檔表頭既有的 `$tt[$colname]` 寫法一致。
- ✅ 3 條 `RAW-FILES` 確認為誤判，已加 `xoops-lint-ignore` 並註明理由：`class/Tadnews.php` 2 處（存在性檢查 / 取副檔名，實際上傳走 `TadUpFiles->upload_file()`）、`demo_upload.php` 1 處（`$_FILES` 僅作參數傳給 `TadUpFiles->upload_one_file()`）。
- ⚠️ **剩餘 3 條為真實違規，但被本路線圖錯估規模**，需獨立成 Phase：見下方 Phase 3b。

### Phase 3b — 分類封面圖改走 TadUpFiles（**儲存模型遷移，非局部改動**）
根因單一：`admin/admin_function.php:114` 的 `mk_thumb()` 直接使用 `\Verot\Upload\Upload`（tadtools 內附的第三方庫），而非 `TadUpFiles`。另 2 條（`admin/admin_function.php:172`、`function.php:182`）是呼叫它的 `if (!empty($_FILES['cate_pic']))` 守衛。

之所以不能在 Phase 3 順手修完：兩者**儲存模型不同**。
- 現況：檔案存為 `/uploads/tadnews/cate/{ncsn}.png`，`tad_news_cate.cate_pic` 欄位存 `"{ncsn}.png"`，樣板與區塊直接組該路徑。
- `TadUpFiles`：存入 `tadnews_files_center` 表，檔名為雜湊值，取用需走 files_center API。

因此改造牽涉：儲存路徑、`cate_pic` 欄位語意、所有讀取 `cate_pic` 的樣板／區塊，**以及既有站台分類圖的資料遷移腳本**。應排在 Phase 4（ENTRY-SQL）之後、與 Phase 5 拆分一併評估，或明確決定「維持現況並永久標註 ignore」。

### Phase 4 — `ENTRY-SQL` 130 → 0（主線，佔全部 error 的 76%）

#### ✅ 試點完成：`admin/newspaper.php` 37 → 0

569 行的全域函式區搬入新建的 `class/Paper.php`（19 個 `public static` 方法），入口檔只留 118 行路由。**行為未變更**。可複製的步驟：

1. **先查跨檔呼叫端**：`grep -rln "\bfn_name\b" --include=*.php .`。本檔 17 個函式**全為檔案內部使用**（`.tpl` 的命中都是 URL 的 `op=` 字串；`email.php` 有自己的 `check_email_mx()` 同名定義），故不需保留包裝函式。**其他檔不保證如此，務必逐一確認。**
2. **檢查搬進 namespace 後會斷掉的參照**：全域**函式**呼叫會自動 fallback 到全域空間，安全；全域**類別**不會，必須加 `\` 前綴。掃描方式：`grep -oE "new [A-Za-z_\\\\][A-Za-z0-9_\\\\]*|[A-Za-z_\\\\][A-Za-z0-9_\\\\]*::"`。本檔全部已 import 或已有 `\`（`\MyTextSanitizer`）。
3. **確認無 `echo` / `print`**，否則搬進 `class/` 會觸發 `ECHO-HTML`。本檔全走 `$xoopsTpl->assign()`。
4. **用腳本做機械轉換**，不要手打：`^function x` → `public static function x`、整區縮排 4 格、函式間互相呼叫改 `self::`。腳本應在行數或預期字串對不上時 **abort**，不要硬改。
5. **入口檔改 `Paper::fn()`**：取代時長名優先排序，否則 `save_newspaper` 會誤吃 `save_newspaper_set`。
6. **清掉入口檔用不到的 `use`**（搬移後 `Tools`/`CkEditor`/`SweetAlert`/`Tmt`/`Utility` 都只剩 class 在用）。
7. **驗收**：`php -l` 兩個檔 + 以 Reflection 確認入口的每個 `Paper::x()` 都對應到實際的 public static 方法 + `xlint .` 比對 error 數。

> PSR-4 自動載入無需註冊：`preloads/autoloader.php` 直接把 `XoopsModules\Tadnews\Paper` 對映到 `class/Paper.php`。

#### ✅ 第 2 檔完成：`admin/admin_function.php` 16 → 0（整檔刪除）

10 個全域函式搬入新建的 `class/Cate.php`（`public static`）。與試點的差別：**這次有跨檔呼叫端**（`admin/main.php` 7 處、`admin/page.php` 7 處、`function.php` 1 處），全部直接改為 `Cate::`，未留包裝函式。搬完後原檔只剩 use 敘述，已整檔刪除，並移除 5 個入口檔的 `require_once admin_function.php`。

順帶修掉一個潛在地雷：`function.php:183` 原本呼叫 `mk_thumb()`，但 `function.php` 會被前台入口載入、`admin_function.php` 不會 —— 前台若走到該分支就是 fatal error。改走自動載入的 `Cate::mk_thumb()` 後不再有這個順序依賴。

> ⚠️ **Phase 3b 位置變更**：`mk_thumb()` 的 2 條 `RAW-FILES` 隨函式搬到 `class/Cate.php:122,180`，總數不變。

#### ✅ 第 3 檔完成：`admin/tag.php` 10 → 0

6 個函式搬入 `class/Tag.php`，入口從 144 行縮到 47 行（純路由）。無跨檔呼叫端，與試點同樣單純。

#### ✅ 第 4 檔完成：`function.php` 10 → 0

5 個函式**分拆到兩個既有類別**：`get_newspaper_set` / `get_newspaper` / `preview_newspaper` → `Paper.php`；`tad_news_cate_form` / `update_tad_news_cate` → `Cate.php`。外部呼叫端 10 處（`email.php`、`newspaper.php`、`admin/newspaper.php`、`admin/main.php`、`admin/page.php`、`page.php`）。

**`function.php` 不可刪除**：它除了函式外還有 `$Tadnews = new Tadnews();` 這個全域物件初始化，被全模組 `global $Tadnews` 依賴。搬走函式後保留 13 行的 bootstrap。

#### ✅ 第 5 檔完成：`page.php` 10 → 0（新建 `class/Page.php`）

4 個函式 + **switch `default` 分支裡的行內 SQL** 一併搬入。行內 SQL 收成 `Page::get_title($nsn)`（取單頁標題供麵包屑用）—— 這是前幾檔沒遇過的形態：**`ENTRY-SQL` 不一定都在函式裡，也可能直接寫在路由分支中**，抽取腳本要同時處理。

**歸屬決策**：原規劃把 `page.php` 併入 `Cate.php`，實作時改為新建 `class/Page.php`。理由：這 4 個函式是**單頁（`tad_news.news_kind='page'`）的顯示與列表**，不是分類 CRUD；放進名為「分類管理」的類別會誤導後續維護者。`admin/page.php`（5 條）之後也歸此類別。

#### ✅ 第 6 檔完成：`admin/import.php` 9 → 0（新建 `class/Importer.php`）

5 個函式搬入，入口從 157 行縮到 38 行。

**歸屬決策**：原規劃併入 `class/Update.php`，實作時改為新建 `class/Importer.php`。理由：`Update.php` 是**模組自身的版本升級**（`chk_chkN`/`go_updateN` 探針與 ALTER），`import.php` 是**從另一個模組（舊 news）匯入資料**的一次性遷移工具 —— 兩者生命週期與觸發時機都不同，混在一起會讓 `Update.php` 變成雜物櫃。外部表（`topics`、`stories`）的唯讀存取因此集中在 `Importer.php`，已於類別 docblock 註明。

**流程改進**：本檔起將「抽取」與「rewire 呼叫端」合併為**單一腳本、最後才寫檔**，徹底消除第 3 檔記錄的破窗期。後續各檔沿用此作法。

#### 🔴 最重要的教訓：搬移會回頭打破**已經搬過**的類別

把 `get_newspaper_set()` 等搬進 `Paper.php` 時，`Paper.php` 裡**先前搬入**的方法仍以裸全域函式呼叫它們（11 處）。那些全域函式此刻已不存在 → 執行期 fatal。抽取腳本只轉換了「新附加的區塊」，沒回頭處理既有內容。

更糟的是驗收腳本當時**掃不到**：它只 glob `*.php` 與 `admin/*.php`，漏了 `class/`。是補掃 `class/` 後才浮現。

**因此驗收腳本必須：**
- 掃描範圍涵蓋 `*.php`、`admin/*.php`、**`class/*.php`**、`blocks/*.php`
- 比對時用 `(?<!function )` 排除定義行，避免把方法定義誤判成呼叫
- 每搬完一個函式，就把它加進「舊全域函式名稱」清單永久保留 —— 這份清單是防止後續搬移互相破壞的唯一保險

#### ⚠️ 腳本撰寫的兩個教訓（第 3 檔踩到）
1. **邊界要用內容斷言，不要只算行數**。第一次抽取算錯一行（把 `/*---function區---*/` 當成首個函式的註解），是腳本裡的 `strpos($all[$i], '編輯表單')` 守衛擋下來的。**每個 slice 邊界都要配一個內容斷言。**
2. **抽取與 rewire 之間有破窗期**。抽取腳本截斷了入口檔後、rewire 腳本因 CRLF 比對失敗而中止，那段期間入口檔呼叫的是已不存在的全域函式。字串比對一律要能容忍 `\r\n`（逐行 regex，不要整塊 `strpos`），且**腳本必須在最後才寫檔**，中止時不留半套。

#### ⚠️ 工具環境陷阱（會重複踩到）
在 Git Bash 下用 `php -r '...'` 執行含**反斜線命名空間**的程式碼時，MSYS 的路徑轉換會把類別名弄壞，造成 `class_exists()` 假性失敗。**驗證腳本一律寫成 `.php` 檔再執行**，不要用 `php -r` 內嵌。

#### ✅ 第 7 檔完成：`index.php` 6 → 0（新建 `class/Sign.php`）

**只搬有 SQL 的部分**：3 個簽收函式（`have_read`／`list_sign`／`list_user_sign`，皆操作 `tad_news_sign`）搬入新建的 `class/Sign.php`；`chk_always_top()` 是新聞置頂維護、不屬簽收，併入 `class/Tadnews.php` 作為 `public static`。

**刻意不搬的部分**：另外 5 個函式（`list_tad_summary_news`／`list_tad_all_news`／`list_tad_tag_news`／`list_tad_cate_news`／`show_news`）**完全沒有 SQL**，只是對 `$Tadnews` 物件的編排。鐵則禁的是「入口檔寫 SQL」，不是「入口檔不得有函式」——不搬不需要搬的東西，維持最小差異。

跨檔呼叫端：`my_news.php:36` 的 `have_read()`（`grep` 時注意 `show_news` 在 `app_api.php`／`TadNewsRest.php` 的命中是**同名的物件方法**，不是這個全域函式）。

#### 其餘 10 檔（32 條）
按違規數由大到小，**一檔一 commit，改完即跑 `xlint .`**：

（全數完成）

建議歸屬（對齊 Phase 5 拆分目標，避免再養胖 `class/Tadnews.php`）：

| 來源 | 目標類別 |
|---|---|
| `admin/tag.php` | `class/Tag.php`（新建） |
| `admin/main.php`、`admin/page.php`、`page.php`、`admin/save_cate_sort.php`、`admin/save_drag.php`、`save_sort.php` | `class/Cate.php`（**已建立**，續加方法即可） |
| `newspaper.php`、`email.php` | `class/Paper.php`（已建立） |
| `admin/import.php` | ~~`class/Update.php`~~ → **`class/Importer.php`（已建立）** |
| `index.php`、`archive.php`、`save.php`、`mk_rss.php`、`function.php`、`admin/admin_function.php` | 視函式職責分派到上述類別，新聞/單頁核心才留 `class/Tadnews.php` |

SQL 搬進 Class 時，`function.php` / `admin/admin_function.php` 的全域函式自然一併遷入並轉為 `public static`——**兩件事是同一件事，不分開排**。遷移時原函式保留為一行包裝呼叫新方法，呼叫端零改動。

### Phase 5 — 拆分 `class/Tadnews.php`（error 歸零後才動）
- 依功能拆出 `class/Cate.php`（分類）、`class/Paper.php`（電子報）、`class/Tag.php`（標籤），`Tadnews.php` 僅保留新聞 / 單頁核心 CRUD。
- 消化 `NON-STATIC` 67 + `RAW-SUPERGLOBAL` 57。
- **前置條件**：Phase 1–4 完成，lint error = 0，才有可靠的回歸網。

### Phase 6 — 收尾
- 樣板 `HEADING` 26（`<h1>`/`<h2>` 改自 `<h3>` 起）、`IMG-SIZE` 1、`HARDCODED-ZH` 6。
- `class/Constants.php` 目前僅 `DISALLOW` 一個常數，決定補齊或移除。
- 確認 `class/TadNewsRest.php` 的過濾 / 權限與一般 op 路由一致（REST 常是漏網之魚）。
- PHP 8 語法現代化，逐檔進行。
 . "\n")` —— **`admin/main.php` 與 `admin/page.php` 雙雙變成 1 byte**。靠 scratchpad 的 `.bak` 還原後重跑才救回。

肇因有二，都是我自己違反已寫下的規則：
1. 用 bash heredoc 產生含大量反斜線的 PHP 腳本（文件已載明此環境會弄壞反斜線，應一律用檔案工具寫）。
2. 腳本沒有輸出守衛。

**因此所有改寫腳本一律加上兩道守衛：**
```php
$new = preg_replace($pattern, $replacement, $orig);
if (null === $new) { exit('ABORT: regex failed: ' . preg_last_error_msg()); }
if ('' === trim($new) || strlen($new) < strlen($orig) * 0.9) { exit('ABORT: 結果異常'); }
```
`preg_replace()` 失敗回傳 `null` 而非拋例外，是這類「靜默清空檔案」事故的標準來源。

#### ✅ 最後 7 檔完成（19 → 0）

| 檔案 | 條 | 去處 |
|---|---:|---|
| `save.php` | 4 | `Cate::add_tad_news_cate()` |
| `admin/save_drag.php` | 3 | `Cate::save_drag()` + `Cate::chk_cate_path()` |
| `admin/save_cate_sort.php` | 1 | `Cate::save_cate_sort()` |
| `email.php` | 2 | `Paper::update_mail()` + `Paper::check_email_format()` |
| `newspaper.php` | 3 | `Paper::list_newspaper()` |
| `archive.php` | 2 | `Tadnews::month_list()`（`archive()` 無 SQL，留在入口） |
| `mk_rss.php` | 4 | **整檔刪除（死檔）** |

三點值得記錄：

**1. `mk_rss.php` 是死檔。** 全站無任何檔案 `require` 它，內容僅一個函式定義、無頂層執行碼；`backend.php` 自帶一份改用 `$Tadnews` 物件（無裸 SQL）的取代版本。直接刪除，而非搬進類別。

**2. 兩個 `check_email_mx()` 行為不同，刻意不合併。** `admin/newspaper.php` 版（已成 `Paper::check_email_mx()`）會做 DNS 查詢（`checkdnsrr` MX/A/CNAME）；`email.php` 版只做 regex 即回傳。合併會讓前台訂閱突然要求 DNS 可解析，屬行為變更。前台版改名為 `Paper::check_email_format()` 並存。

> ⚠️ **兩者共有的既存缺陷**：`if (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) return true;` —— 第一個 regex 比對的是**不合法**樣式（雙 @、連續點…），命中卻回傳 true，判斷方向相反。搬移時原樣保留，未修正；應另案處理。

**3. 入口檔的 AJAX 端點需自行確保 autoloader。** `admin/save_drag.php` / `admin/save_cate_sort.php` 只 `require` 核心的 `include/cp_header.php`，不走模組 `header.php`。比照 `interface.php` 既有寫法加上防禦性載入：

```php
if (!class_exists('XoopsModules\Tadnews\Cate')) {
    require XOOPS_ROOT_PATH . '/modules/tadnews/preloads/autoloader.php';
}
```
按違規數由大到小，**一檔一 commit，改完即跑 `xlint .`**：

（全數完成）

建議歸屬（對齊 Phase 5 拆分目標，避免再養胖 `class/Tadnews.php`）：

| 來源 | 目標類別 |
|---|---|
| `admin/tag.php` | `class/Tag.php`（新建） |
| `admin/main.php`、`admin/page.php`、`page.php`、`admin/save_cate_sort.php`、`admin/save_drag.php`、`save_sort.php` | `class/Cate.php`（**已建立**，續加方法即可） |
| `newspaper.php`、`email.php` | `class/Paper.php`（已建立） |
| `admin/import.php` | ~~`class/Update.php`~~ → **`class/Importer.php`（已建立）** |
| `index.php`、`archive.php`、`save.php`、`mk_rss.php`、`function.php`、`admin/admin_function.php` | 視函式職責分派到上述類別，新聞/單頁核心才留 `class/Tadnews.php` |

SQL 搬進 Class 時，`function.php` / `admin/admin_function.php` 的全域函式自然一併遷入並轉為 `public static`——**兩件事是同一件事，不分開排**。遷移時原函式保留為一行包裝呼叫新方法，呼叫端零改動。

### Phase 5 — 拆分 `class/Tadnews.php`（error 歸零後才動）
- 依功能拆出 `class/Cate.php`（分類）、`class/Paper.php`（電子報）、`class/Tag.php`（標籤），`Tadnews.php` 僅保留新聞 / 單頁核心 CRUD。
- 消化 `NON-STATIC` 67 + `RAW-SUPERGLOBAL` 57。
- **前置條件**：Phase 1–4 完成，lint error = 0，才有可靠的回歸網。

### Phase 6 — 收尾
- 樣板 `HEADING` 26（`<h1>`/`<h2>` 改自 `<h3>` 起）、`IMG-SIZE` 1、`HARDCODED-ZH` 6。
- `class/Constants.php` 目前僅 `DISALLOW` 一個常數，決定補齊或移除。
- 確認 `class/TadNewsRest.php` 的過濾 / 權限與一般 op 路由一致（REST 常是漏網之魚）。
- PHP 8 語法現代化，逐檔進行。
