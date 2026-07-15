# TadNews VSCode AI 常用 Prompts

## 1. 分析目前檔案，不修改

```text
請先讀取以下專案規則：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請分析目前開啟的檔案。

第一輪只分析，不要修改程式碼。

請依照以下方向檢查：

1. 安全性
2. 資料庫讀取效率
3. WCAG 2.2 AAA
4. 程式碼維護性

請輸出：

1. P0 到 P5 問題清單
2. 建議優先修改順序
3. 哪些問題需要人工確認
4. 測試建議
5. 回滾建議
```

## 2. 只修 SQL Injection

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請只修正目前檔案中的 SQL Injection 風險。

限制：

1. 不改變功能
2. 不改變畫面
3. 不改變資料表
4. 不改變 URL
5. 不重構架構
6. 不改變 Smarty 變數名稱

要求：

1. 所有數字 ID 必須轉成 int
2. 所有外來字串必須安全處理
3. SQL 不得直接使用未驗證輸入
4. 保留 XOOPS 相容寫法
5. 修改後列出測試方式
```

## 3. 只修 XSS

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請只修正目前檔案中的 XSS 風險。

限制：

1. 不改變功能
2. 不改變畫面
3. 不改變資料流程
4. 不改變模板變數名稱

要求：

1. PHP 輸出到 HTML 時使用 htmlspecialchars
2. Smarty 輸出使用 escape modifier
3. URL 屬性使用適合 URL 的 escape
4. HTML 屬性使用適合 HTML attribute 的 escape
5. 不要重構其他邏輯
```

## 4. 只修 CSRF

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請只修正目前檔案或表單的 CSRF 問題。

要求：

1. 表單中加入 XOOPS token
2. POST 接收時加入 token check
3. 驗證失敗時使用 redirect_header()
4. 驗證失敗後要 exit
5. 不改變欄位名稱
6. 不改變表單 action
7. 不改變原有權限邏輯
```

## 5. 資料庫效能分析，不修改

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請分析目前檔案的資料庫查詢效能。

第一輪只分析，不要修改。

請找出：

1. SELECT *
2. 無 LIMIT 的列表查詢
3. 迴圈內 SQL
4. N+1 Query
5. 不必要的 COUNT
6. 可用 JOIN 改善的查詢
7. 可能需要索引的 WHERE / ORDER BY / JOIN 欄位

請輸出：

1. 問題位置
2. 影響
3. 建議 SQL 改法
4. 建議索引
5. 測試方式
```

## 6. 只修列表查詢效能

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請只修正目前檔案中的列表查詢效能問題。

要求：

1. 避免 SELECT *
2. 加入合理 LIMIT
3. 保留原本排序
4. 保留原本篩選條件
5. 不改變模板變數名稱
6. 若需要索引，只提供 SQL 建議，不直接改 schema
7. 修改後列出測試方式
```

## 7. Smarty WCAG 2.2 AAA 分析

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請依照 WCAG 2.2 AAA 檢查目前 Smarty 模板。

第一輪只分析，不要修改。

請檢查：

1. img 是否缺少 alt
2. input / select / textarea 是否缺少 label
3. 錯誤訊息是否缺少 aria-describedby
4. 連結文字是否不明確
5. 按鈕文字是否不明確
6. heading 結構是否不合理
7. table 是否缺少 caption、th、scope
8. 是否有只能用滑鼠操作的元素
9. 是否有 focus 樣式問題
10. 是否只用顏色傳達資訊

請列出對應 WCAG Success Criterion。
```

## 8. 只修 Smarty 表單無障礙

```text
請讀取：

- .ai/00-project-context.md
- .ai/01-xoops-refactor-skill.md

請只修正目前 Smarty 模板中的表單無障礙問題。

要求：

1. 每個 input / select / textarea 都要有對應 label
2. 錯誤訊息使用 aria-describedby
3. 必填欄位不可只用顏色表示
4. 不改變 Smarty 變數名稱
5. 不改變表單 action
6. 不改變欄位 name
7. 不改變原本後端接收邏輯
```

## 9. 建立分析報告

```text
請根據目前檔案的分析結果，建立 Markdown 報告，建議存到：

.ai/reports/[檔名]-analysis.md

報告格式：

# [檔名] 重構分析報告

## 一、問題摘要

| 優先級 | 類別 | 位置 | 問題 | 建議 |
|---|---|---|---|---|

## 二、安全性

## 三、資料庫效能

## 四、WCAG 2.2 AAA

## 五、維護性

## 六、建議修改順序

## 七、測試方式

## 八、回滾方式
```

## 10. AI 修改後自我檢查

```text
請根據：

- .ai/04-review-checklist.md

檢查你剛剛的修改是否符合要求。

請輸出：

1. 修改摘要
2. 是否有違反限制
3. 安全性檢查結果
4. XOOPS 相容性檢查結果
5. 需要我人工確認的地方
```
