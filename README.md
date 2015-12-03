# Jajjimento

Jajjimento（ジャッジメント）中文涵意是風紀委員，這是用來驗證表單的 PHP 類別，

用法簡單，你甚至可以先將設定存至檔案，假若要重複使用該規則，只需要幾條指令。

和以往的版本不同，這次比較偏向物件導向方式。

## 舉例

```php
/** 設定要檢查的來源為 $_POST 陣列 */
$jaji->source($_POST)

     /** 新增一個規則 */
     ->add('username')   // 檢查 $_POST['username']
     ->type('length')    // 種類「長度」
     ->min(3)            // 最短 3 個字
     ->max(16)           // 最長 16 個字
     ->required()        // 必填項目
     
     /** 未來你熟悉之後，想要簡短，你就可以像這樣縮成一行 */
     ->add('age')    ->type('range') ->min(0)->max(12)
     ->add('address')->type('length')->min(1)->max(33)->required()
     
     /** 一旦設定都好了，接下來就該檢查了！ */
     ->check();
     
     
```
