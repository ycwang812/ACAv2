# 模組 3 – 引導式實驗：託管靜態網站
[//]:# "SKU：ILT-TF-200-ACACAD-2    源課程：ILT-TF-100-ARCHIT-6 branch dev_65"

## 實驗概覽與目標
靜態網站的內容是固定的，未經過後端處理。網站可能包含 HTML 頁面、圖像、樣式表以及呈現網站所需的所有檔，但不使用伺服器端腳本或資料庫。如果您希望靜態網頁具有交互性並運行程式設計邏輯，可以借助在使用者 Web 流覽器中運行的 JavaScript 來實現。

您可以上傳內容並使其公開可訪問，從而在 Amazon Simple Storage Service (Amazon S3) 上輕鬆託管靜態網站。Amazon S3 可支援您從 Web 上的任何位置隨時存儲和檢索任意數量的資料，而無需任何伺服器。

完成本實驗後，您應當能夠：

- 在 Amazon S3 中創建存儲桶
- 將內容上傳到存儲桶
- 允許訪問存儲桶物件
- 更新網站

## 時長

完成本實驗大約需要 **20 分鐘**。

## AWS 服務限制
本實驗環境中對 AWS 服務和服務操作的訪問可能僅以完成實驗說明為限。如果您嘗試訪問其他服務或者執行本實驗所述之外的操作，可能會出錯。

## 訪問 AWS 管理主控台

1. 在本說明上方，選擇 <span id="ssb_voc_grey">Start Lab</span>（開始實驗）以啟動實驗。

   此時將打開 **Start Lab**（開始實驗）面板，其中顯示實驗狀態。

   <i class="fas fa-info-circle"></i> **提示**：如果您需要更多時間來完成實驗，請重新選擇 <span id="ssb_voc_grey">Start Lab</span>（開始實驗）按鈕來重新開機環境的計時器。

2. 請等待 **Start Lab**（開始實驗）面板顯示消息 *Lab status: ready*（實驗狀態：就緒）後，再選擇 **X** 關閉該面板。

3. 在本說明上方，選擇 <span id="ssb_voc_grey">AWS</span>。

   此操作將會在新的流覽器標籤頁中打開 AWS 管理主控台。您將自動登錄系統。

   <i class="fas fa-exclamation-triangle"></i> **提示**：如果未打開新的流覽器標籤頁，那麼您的流覽器頂部通常會有一個橫幅或圖示，同時顯示一條消息，指明您的流覽器阻止該網站打開快顯視窗。選擇該橫幅或圖示，然後選擇 **Allow pop ups**（允許快顯視窗）。

4. 排列 **AWS 管理主控台**標籤頁，使其與本說明並排顯示。理想情況下，您將同時打開這兩個流覽器標籤頁，從而更輕鬆地執行實驗步驟。

   <i class="fas fa-exclamation-triangle"></i> **除非特別要求，否則請勿更改區域**。

## 任務 1：在 Amazon S3 中創建存儲桶

在此任務中，您將創建 S3 存儲桶並將其配置用於託管靜態網站。

5. 在 **AWS 管理主控台**的 <span id="ssb_services">Services <i class="fas fa-angle-down"></i></span>（服務）功能表上，選擇 **S3**。

6. 選擇 <span id="ssb_orange">Create bucket</span>（創建存儲桶）。

   S3 存儲桶名稱是全域唯一的，並且命名空間由所有 AWS 帳戶共用。在創建存儲桶之後，任何 AWS 區域中的其他 AWS 帳戶均無法使用該存儲桶的名稱，除非您刪除該存儲桶。

   在本實驗中，您將使用包含隨機編號的存儲桶名稱，例如：_website-123_

7. 對於 **Bucket name**（存儲桶名稱），輸入 `website-<123>`（將 <_123_> 替換為亂數字）

   系統預設阻止對存儲桶進行公共訪問。由於靜態網站中的檔需要通過互聯網進行訪問，您必須允許公共訪問。

   - 驗證 **AWS Region**（AWS 區域）是否設置為 **us-east-1**（如果不是，請選擇 us-east-1 區域）

8. 在 **Object Ownership**（物件所有權）部分中，選擇 **ACLs enabled**（ACL 已啟用），然後驗證是否已選擇 **Bucket owner preferred**（存儲桶擁有者優先）。

9. 清除 **Block all public access**（阻止所有公共訪問），然後選中 **I acknowledge that the current settings may result in this bucket and the objects within becoming public**（我確認當前設置可能會導致此存儲桶及其中的物件變為公開）框。

10. 選擇 <span id="ssb_orange">Create bucket</span>（創建存儲桶）。

   您可以使用標籤向存儲桶添加其他資訊，例如專案代碼、成本中心或存儲桶。

11. 選擇新存儲桶的名稱。

12. 選擇 **Properties**（屬性）標籤頁。

13. 滾動到 **Tags**（標籤）面板。

14. 選擇 <span id="ssb_white">Edit</span>（編輯），然後選擇 <span id="ssb_white">Add tag</span>（添加標籤）並輸入：

    - **Key**（鍵）：`Department`
    - **Value**（值）：`Marketing`

15. 選擇 <span id="ssb_orange">Save changes</span>（保存更改）以保存標籤。

    接下來，您將配置存儲桶用於靜態網站託管。

16. 停留在 **Properties**（屬性）控制台中。

17. 滾動到 **Static website hosting**（靜態網站託管）面板。

18. 選擇 <span id="ssb_white">Edit</span>（編輯）

19. 配置以下設置：

    - **Static web hosting**（靜態 Web 託管）：**Enable**（啟用）
    - **Hosting type**（託管類型）：**Host a static website**（託管靜態網站）
    - **Index document**（索引文檔）：`index.html`
      - **注意**：即使此值已經顯示，也必須手動輸入。
    - **Error document**（錯誤文檔）：`error.html`

20. 選擇 <span id="ssb_orange">Save changes</span>（保存更改）

21. 在 **Static website hosting**（靜態網站託管）面板中，選擇 **Bucket website endpoint**（存儲桶網站終端節點）下的連結。

    由於尚未配置存儲桶許可權，您將會收到 *403 Forbidden*（403 禁止）消息。讓該標籤頁在 Web 流覽器中保持打開狀態，以便稍後返回。

    您的存儲桶現已配置為可以託管靜態網站。

## 任務 2：將內容上傳到存儲桶

在此任務中，您將用作靜態網站的檔上傳到存儲桶。

22. 按右鍵各個連結並將檔下載到您的電腦上：

    <i class="fas fa-exclamation-triangle"></i> 請確保所有檔案名保持不變，包括副檔名在內。

    - [index.html](../website/index.html)
    - [script.js](../website/script.js)
    - [style.css](../website/style.css)

23. 返回到 Amazon S3 控制台，並在之前創建的 `website-<123>` 存儲桶中選擇 **Objects**（物件）標籤頁。

24. 選擇 <span id="ssb_orange">Upload</span>（上傳）

25. 選擇 <span id="ssb_white">Add files</span>（添加檔）

26. 找到並選擇您下載的三個檔。

27. 如果系統提示，請選擇 <i class="far fa-check-square"></i> I acknowledge that existing objects with the same name will be overwritten（我確認具有相同名稱的現有物件將被覆蓋）。

28. 選擇 <span id="ssb_orange">Upload</span>（上傳）

    您的檔將上傳到存儲桶。

    - 選擇 <span id="ssb_orange">Close</span>（關閉）

## 任務 3：允許訪問物件

預設情況下，存儲在 Amazon S3 中的物件為私有物件。這樣可以確保組織的資料安全。

在此任務中，您要將上傳的物件設置為可公開訪問。

首先，您需要確認物件目前是私有物件。

29. 返回到顯示 *403 Forbidden*（403 禁止）消息的流覽器標籤頁。

30. 刷新 <i class="fas fa-sync"></i> 網頁。

    <i class="fas fa-comment"></i> 如果您無意中關閉了此標籤頁，請轉到 **Properties**（屬性）標籤頁，在 **Static website hosting**（靜態網站託管）面板中，再次選擇 **Endpoint**（終端節點）連結。

    此時仍會顯示 *403 Forbidden*（403 禁止）消息。

    *分析*：此回應是正常的。此消息說明您的靜態網站正由 Amazon S3 託管，但內容是私有的。

    您可以通過兩種不同的方式將 Amazon S3 物件設置為可公開訪問：

    - 要將整個存儲桶或存儲桶中的特定目錄設置為可公開訪問，請使用*存儲桶策略*。
    - 要將存儲桶中的單個物件設置為可公開訪問，請使用*存取控制清單 (ACL)*。

    比較安全的做法是將*單個物件*設置為可公開訪問，這樣可避免將其他物件誤設置為可公開訪問。不過，如果您確定整個存儲桶不包含任何敏感資訊，也可以使用*存儲桶策略*。

    現在，您可以將單個物件配置為可公開訪問。

31. 使用 Amazon S3 控制台返回到 Web 流覽器標籤頁（但不要關閉該網站標籤頁）。

32. 選擇 <i class="far fa-check-square"></i> 所有三個物件。

33. 在 <span id="ssb_white">Actions <i class="fas fa-angle-down"></i></span>（操作）功能表中，選擇 **Make public via ACL**（通過 ACL 公開）。

    此時將顯示三個物件的清單。

34. 選擇 <span id="ssb_white">Make public</span>（設為公開）。

    您的靜態網站現在可以公開訪問了。

35. 返回到顯示 *403 Forbidden*（403 禁止）消息的 Web 流覽器標籤頁。

36. 刷新 <i class="fas fa-sync"></i> 網頁。

   此時顯示的就是由 Amazon S3 託管的靜態網站。

## 任務 4：更新網站

您可以通過編輯 HTML 檔並將其重新上傳到 S3 存儲桶來更新網站。

Amazon S3 是一項*物件存儲服務*，因此您必須上傳整個檔。此操作將替換存儲桶中的現有物件。您無法編輯物件的內容，而必須替換整個物件。

37. 在您的電腦上，將 **index.html** 檔載入到文字編輯器中（例如 Notepad 或 TextEdit）。

38. 查找文本 **Served from Amazon S3**，然後將其替換為 `Created by <YOUR-NAME>`，用您的姓名（例如 _Created by Jane_）替換 <*YOUR-NAME*>。

39. 保存檔。

40. 返回到 Amazon S3 控制台並上傳剛才編輯的 **index.html** 文件。

41. 選擇 <i class="far fa-check-square"></i> **index.html**，然後使用 **Actions**（操作）功能表以再次選擇 **Make public via ACL**（通過 ACL 公開）選項。

42. 通過靜態網站返回到 Web 流覽器標籤頁，然後刷新 <i class="fas fa-sync"></i> 頁面。

    此時頁面上會顯示您的姓名。



現在您的靜態網站可以通過互聯網進行訪問了。由於託管在 Amazon S3 上，網站具有較高的可用性，並且可以承受非常大的流量而無需使用任何伺服器。

您還可以使用自訂功能變數名稱將用戶定向到託管在 Amazon S3 上的靜態網站。要實現這一點，您可以將 Amazon Route 53 網域名稱系統 (DNS) 服務與 Amazon S3 結合使用。

## 提交作業

**提示**：本實驗的評分腳本假定您只有一個名稱包含單詞 `website` 的存儲桶。請驗證情況是否符合，如有必要，請刪除之前創建的任何舊存儲桶，以便評分腳本為正確的存儲桶進行內容和設置評分。

43. 在本說明上方，選擇 <span id="ssb_blue">Submit</span>（提交）以記錄您的進度，並在出現提示時選擇 **Yes**（是）。

44. 如果在幾分鐘後仍未顯示結果，請返回到本說明上方，並選擇 <span id="ssb_voc_grey">Grades</span>（成績）

   **提示**：您可以多次提交作業。更改作業後，再次選擇 **Submit**（提交）。您最後一次提交的作業將記為本實驗內容的作業。

45. 要查找有關作業的詳細回饋，請選擇 <span id="ssb_voc_grey">Details</span>（詳細資訊），然後選擇 <i class="fas fa-caret-right"></i> **View Submission Report**（查看提交報告）。


## 實驗完成 <i class="fas fa-graduation-cap"></i>

<i class="fas fa-flag-checkered"></i> 恭喜！您已完成本實驗。

46. 選擇此頁面頂部的 <span id="ssb_voc_grey">End Lab</span>（結束實驗），然後選擇 <span id="ssb_blue">Yes</span>（是）確認您要結束實驗。

    此時將顯示一個面板，其中指示 *DELETE has been initiated...You may close this message box now.*（刪除操作已啟動... 您現在可以關閉此訊息方塊）。

47. 選擇右上角的 **X**，關閉面板。



*©2023 Amazon Web Services, Inc. 和其附屬公司。保留所有權利。未經 Amazon Web Services, Inc. 事先書面許可，不得複製或轉載本文的部分或全部內容。禁止因商業目的複製、出借或出售本文。*
