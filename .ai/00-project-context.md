# TadNews XOOPS 模組重構專案背景

## 專案資訊

- 模組名稱：tadnews
- 模組用途：XOOPS 最新消息 / 新聞 / 自訂頁面模組
- 本機路徑：`C:\Users\tad\tn\UniServerZ\www\modules\tadnews`
- GitHub：`https://github.com/tad0616/tadnews`
- 預設分支：master

## 已知專案狀態

根據 README 紀錄，本模組近年已經有持續維護，包含：

- 支援 XOOPS 2.5.11
- 支援 Bootstrap 5
- 已移除部分過時功能
- 曾進行無障礙 AA 修正
- 曾進行安全性修正
- 曾進行外來變數過濾
- 曾將部分資料庫語法參數化
- 曾使用 `$xoopsDB->escape()` 取代 `$myts->addSlashes()`
- 曾新增資料表索引以提升讀取速度

因此本次重構不應視為完整重寫，而應採用「漸進式強化」。

## 本次重構目標

1. 增加資料庫讀取效率
2. 從既有無障礙 AA 基礎提升到 WCAG 2.2 AAA
3. 優化程式碼，使其容易維護
4. 增加安全性

## 重構基本原則

- 不一次大改整個模組
- 優先保持現有功能相容
- 優先保持 XOOPS 2.5.11 相容
- 優先保持現有資料表相容
- 優先保持現有 URL 相容
- 優先保持現有 Smarty 變數名稱相容
- 優先保持現有模板檔案名稱相容
- 所有修改必須可由 Git 回滾

## 預設限制

除非使用者明確同意，AI 不得：

- 重新設計整個架構
- 大量改名 class、function、template 變數
- 任意修改資料表欄位名稱
- 任意刪除既有功能
- 任意改變 URL 結構
- 任意改變權限邏輯
- 任意升級到 XOOPS 不支援的 PHP 語法
- 任意引入 Composer 套件
- 任意引入大型前端框架

## 優先處理檔案方向

第一階段建議先檢查：

- `index.php`
- `post.php`
- `save.php`
- `page.php`
- `archive.php`
- `my_news.php`
- `ajax_list_content.php`
- `ajax_table_content.php`
- `app_api.php`
- `include/`
- `class/`
- `templates/`
- `blocks/`

## 分析順序

1. 安全性 P0 / P1
2. 資料庫查詢效能
3. WCAG 2.2 AAA
4. 程式碼維護性
5. 架構重構
