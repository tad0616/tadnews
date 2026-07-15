# TadNews AI 修改 Review Checklist

## 一、通用檢查

- [ ] 是否保留原功能
- [ ] 是否沒有任意刪除功能
- [ ] 是否沒有任意改 URL
- [ ] 是否沒有任意改資料表欄位名稱
- [ ] 是否沒有任意改 Smarty 變數名稱
- [ ] 是否沒有引入不必要相依套件
- [ ] 是否符合 XOOPS 2.5.11 相容性
- [ ] 是否符合專案既有程式風格

## 二、安全性檢查

- [ ] `$_GET` 已驗證或轉型
- [ ] `$_POST` 已驗證或轉型
- [ ] `$_REQUEST` 已驗證或轉型
- [ ] SQL 沒有直接拼接未處理輸入
- [ ] HTML 輸出已 escape
- [ ] Smarty 輸出已 escape
- [ ] 表單有 token
- [ ] POST / 修改 / 刪除操作有 token check
- [ ] 後端有權限檢查
- [ ] 沒有 IDOR
- [ ] 沒有 Open Redirect
- [ ] 檔案上傳有檢查副檔名
- [ ] 檔案上傳有檢查 MIME type
- [ ] 檔案名稱已重新命名或淨化
- [ ] 不顯示 debug 資訊

## 三、資料庫檢查

- [ ] 是否避免 `SELECT *`
- [ ] 列表是否有 `LIMIT`
- [ ] 分頁是否正常
- [ ] 是否避免 N+1 Query
- [ ] 是否避免迴圈內 SQL
- [ ] WHERE / ORDER BY / JOIN 欄位是否有索引建議
- [ ] 是否仍使用 `$xoopsDB->prefix()`
- [ ] 是否未破壞舊資料相容性

## 四、WCAG 2.2 AAA 檢查

- [ ] 圖片有適當 `alt`
- [ ] 裝飾圖片使用空 `alt`
- [ ] 表單欄位有 `label`
- [ ] 錯誤訊息有 `aria-describedby`
- [ ] 錯誤提示可被螢幕閱讀器讀取
- [ ] 連結文字清楚
- [ ] 按鈕文字清楚
- [ ] 可使用鍵盤操作
- [ ] focus 樣式清楚
- [ ] 不只靠顏色傳達資訊
- [ ] heading 層級合理
- [ ] table 有 `caption`、`th`、`scope`
- [ ] 動態內容有 `aria-live`

## 五、XOOPS 檢查

- [ ] `mainfile.php` 引入路徑正確
- [ ] `header.php` / `footer.php` 引入正確
- [ ] 後台 `admin_header.php` 引入正確
- [ ] `$xoopsDB` 使用方式正確
- [ ] `$xoopsTpl->assign()` 沒有被破壞
- [ ] `$xoopsUser` 權限邏輯正確
- [ ] `$xoopsModuleConfig` 設定讀取正常
- [ ] `redirect_header()` 後有適當 `exit`
- [ ] 語系常數未被破壞
