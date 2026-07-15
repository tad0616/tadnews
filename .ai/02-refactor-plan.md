# TadNews 重構計畫

## 一、重構目標

- [ ] 增加資料庫讀取效率
- [ ] 符合 WCAG 2.2 AAA
- [ ] 優化程式碼與維護性
- [ ] 增加安全性

## 二、目前策略

本專案採用漸進式重構：

1. 先分析，不直接修改
2. 先修安全性 P0 / P1
3. 再修資料庫效能 P2
4. 再修 WCAG 2.2 AAA P3
5. 最後處理維護性 P4 / P5
6. 每次只處理一個檔案或一個明確問題
7. 每次修改後都要測試並 commit

## 三、第一階段：安全性盤點

### 優先檢查檔案

- [ ] `index.php`
- [ ] `post.php`
- [ ] `save.php`
- [ ] `page.php`
- [ ] `archive.php`
- [ ] `my_news.php`
- [ ] `ajax_list_content.php`
- [ ] `ajax_table_content.php`
- [ ] `app_api.php`
- [ ] `email.php`
- [ ] `demo_upload.php`
- [ ] `include/`
- [ ] `class/`

### 檢查項目

- [ ] SQL Injection
- [ ] XSS
- [ ] CSRF
- [ ] 權限繞過
- [ ] IDOR
- [ ] 任意檔案上傳
- [ ] Open Redirect
- [ ] Path Traversal

## 四、第二階段：資料庫效能

### 優先檢查

- [ ] 列表頁查詢
- [ ] 分類查詢
- [ ] 文章詳細頁查詢
- [ ] 標籤查詢
- [ ] 附件查詢
- [ ] 區塊查詢
- [ ] RSS 查詢
- [ ] archive 查詢

### 檢查項目

- [ ] `SELECT *`
- [ ] 無 `LIMIT`
- [ ] N+1 Query
- [ ] 迴圈內 SQL
- [ ] 缺少索引
- [ ] 不必要的 `COUNT`
- [ ] 可否快取

## 五、第三階段：WCAG 2.2 AAA

### 優先檢查

- [ ] `templates/`
- [ ] `blocks/`
- [ ] `admin/`
- [ ] 表單模板
- [ ] 文章列表模板
- [ ] 文章內容模板
- [ ] 分類模板
- [ ] 搜尋模板

### 檢查項目

- [ ] `img alt`
- [ ] `label`
- [ ] `aria-describedby`
- [ ] `role="alert"`
- [ ] 鍵盤操作
- [ ] focus 樣式
- [ ] 色彩對比
- [ ] heading 層級
- [ ] table 結構
- [ ] 連結文字明確性

## 六、第四階段：維護性

### 檢查項目

- [ ] 重複 SQL
- [ ] 重複權限檢查
- [ ] 重複表單驗證
- [ ] 過長函式
- [ ] 過大檔案
- [ ] 可抽出 Repository
- [ ] 可抽出 Service
- [ ] 可抽出 Validator

## 七、目前進度

| 日期 | 檔案 | 類型 | 狀態 | 備註 |
|---|---|---|---|---|
|  |  |  |  |  |
