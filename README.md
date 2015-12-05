&nbsp;

<p align="center">
  <img src="http://i.imgur.com/QqGiSvi.png"/>
</p>
<p align="center">
  <i>Stop it when it's false.</i>
</p>

&nbsp;


# Jajjimento

Jajjimento（ジャッジメント）中文涵意是風紀委員，這是用來驗證表單的 PHP 類別，

用法簡單，你甚至可以先儲存設定，假若要重複使用該規則，只需要幾條指令。

和以往的版本不同，這次比較偏向物件導向方式，且新增了跨站表單檢查功能。

&nbsp;

## 特色

1. 可以儲存規則供未來重複使用。

2. 簡單明瞭。

3. 可以檢查陣列，或是直接檢查變數。

4. 更具有意義的函式名稱卻又簡短。 

5. 支援艾拉。

6. 跨站表單 (CSRF) 檢查。

&nbsp;

## 索引

1. [舉例](#舉例)

2. [設置來源 或 採用預先規則](#設置來源-或-採用預先規則)

  * [來源模式](#來源模式)
  * [手動模式](#手動模式)
  * [採用預先規則](#採用預先規則)

3. [設置種類](#設置種類)

  * [`min()` 和 `max()` 的用法](#min-和-max-的用法)
  * [`dateFormat()` 的用法](#dateformat-的用法)
  * [`inside()` 的用法](#inside-的用法)
  * [`urlNot()` 的用法](#urlnot-的用法)
  * [`target()` 的用法](#target-的用法)

4. [設置附加功能](#設置附加功能)

  * [`required()` 或 `req()` 的用法](#required-或-req-的用法)
  * [`format()` 的用法](#format-的用法)
  * [`trim()` 的用法](#trim-的用法)

5. [驗證](#驗證)

6. [取得驗證後的資料](#取得驗證後的資料)

7. [儲存成預先規則](#儲存成預先規則)

8. [跨站表單驗證（可選）](#跨站表單驗證可選)

  * [原理](#原理)
  * [必須需求](#必須需求])
  * [開關跨站表單檢查](#開關跨站表單檢查)
  * [名稱設定](#名稱設定)
  * [取得麵包屑內容](#取得麵包屑內容)
  * [插入驗證欄位](#插入驗證欄位)
  * [關於 XHR（AJAX）與標頭](#關於-XHR-AJAX-與標頭)

9. [當「艾拉」存在的時候](#當艾拉存在的時候)

&nbsp;

## 舉例

這次的舉例，採用的是多數的情況，你可能會想檢查你接收的 $_POST 表單，你就應該這樣做：

```php
/** 設定要檢查的來源為 $_POST 陣列 */
$jaji->source($_POST)

     /** 稍後會提及如何設定規則 */
     ->add('username')->type('length')->min(3)->max(16)->required()
     
     /** 你也可以用簡寫 */
     ->add('username')->length(3, 16)->req()

     /** 一旦設定都好了，接下來就該檢查了！ */
     ->check();
```

&nbsp;

## 設置來源 或 採用預先規則

在風紀委員中，你有兩種方式可以選擇，讀取預先規則或採用來源模式。

而來源模式分成下列兩種：

* 來源 － 可以是 `$_POST` 或 `$_GET`，甚至是一個*陣列*。
* 手動 － 當你的來源不固定，或者來源是一個變數而非陣列，就應該採用手動。

&nbsp;

#### 來源模式

若你要檢查一個陣列中的資料，你就應該用 `source()` 啟用來源模式。

在這裡以檢查一個 $_POST 表單為例：

```php
$jaji->source($_POST)
     ->add('欄位名稱')   // 假設欄位名稱是 username，那麼你就會檢查 $_POST['username']。
```

&nbsp;

#### 手動模式

今天如果你並沒有陣列要檢查，而是想直接檢查一個變數，

**你則不需要設置 `source()` 而是應該直接進行新增規則的動作。**

```php
$jaji->add($Test)   // 在這個情況，$Test 會直接成為來源，該變數的內容會被檢查。
```

&nbsp;

#### 採用預先規則

你可以利用 `loadCheck()` 直接套用一個你先前儲存的規則並直接檢查，稍後會提及如何儲存一個規則。

```php
$jaji->source($_POST)
     ->loadCheck($rule)   // 接下來會套用 $Rule 內所含有的規則並檢查。
```

&nbsp;

## 設置種類

透過 `type()` 來設定一個規則要檢查的種類，如果你嫌這種用法太長，**我們稍後會提及縮寫**，下列是目前可用的種類:

| 種類英文   |   簡短    |        相關函式      |                                    種類說明                                |
| ---------- | --------- | -------------------- | -------------------------------------------------------------------------- |
| length     | 長度      |  `min()`, `max()`    | 字串長度；字串必須短於 `max()`，也至少長於 `min()` 所設定的值。            |
| range      | 範圍      |  `min()`, `max()`    | 數字範圍；數字必須小於 `max()`，也至少大於 `min()` 所設定的值。            |
| date       | 日期      |  `dateFormat()`      | 日期格式；日期必須是 `dateFormat()` 所設定的格式，例如 `YYYY-MM-DD`。      |
| in         | 清單      |  `inside()`          | 是否存在；這個值必須在 `inside()` 所設定的陣列內。                         |
| email      | 電郵      |                      | 電子信箱；內容必須是符合電子信箱格式。                                     |
| gender     | 性別      |                      | 性別種類；性別必須是 f(emale) 或 m(ale) 或 o(ther)。                       |
| ip         | IP        |                      | IP 地址 ；值必須符合 IPv4 或是 IPv6 的格式。                               |
| ipv4       | IPv4      |                      | IP 地址 ；值必須符合 IPv4 格式。                                           |
| ipv6       | IPv6      |                      | IP 地址 ；值必須符合 IPv6 格式。                                           |
| url        | 網址      |  `urlNot()`          | 網址    ；內容必須是符合一般網址格式，用 `urlNot()` 來新增不允許網址開頭。 |
| equals     | 等於      |  `target()`          | 相同內容；內容必須與 `target()` 的內容相同。                               |

&nbsp;

##### `min()` 和 `max()` 的用法

長度或數字必須大於 `min()` ，且小於或短於 `max()` 所設定的值。

```php
->add('username')->type('length')->min(3)->max(6)
```

&nbsp;

##### `dateFormat()` 的用法

`dateFormat()` 用來設定什麼日期格式可以被接受，

可以是單一個格式，或是一整個陣列，日期格式為 [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601)，

簡單說就是你在 PHP 中常見的「YYYY-mm-dd」或「dd/mm/YYYY」之類。

```php
->add('username')->type('date')->dateFormat('YYYY-mm-dd')

/** 或是 */
->add('birthday')->type('date')->dateFormat(['YYYY-mm-dd', 'mm/dd/YYYY'])
```

&nbsp;

##### `inside()` 的用法

必須在 `inside()` 所指定陣列中找的到這個值。

```php
->add('options')->type('in')->inside(['A', 'B', 'C', 'D'])
```

&nbsp;

##### `urlNot()` 的用法

網址不可以是以什麼開頭的，例如 `http` 或 `https`，設定可以是單一個，或是陣列。

```php
->add('url')->type('url')->urlNot('ftp')

/** 或是 */
->add('url')->type('url')->urlNot(['http', 'https'])

/** 你也可以 */
->add('url')->type('url')->urlNot(['http://www.google.com/', 'http://www.yahoo.com/'])
```

&nbsp;

##### `target()` 的用法

必須在 `target()` 中的內容一樣。需要注意的是：

**無論是來源或是手動模式中，`target()` 帶入的是欄位名稱，而不是變數。**

**但是如果你帶入一個變數，則將第二個參數設為 False 。**

```php
->add('passwordConfirm')->type('equals')->target('password')

/** 倘若你要帶入一個變數，將後面設為 False */
->add('passwordConfirm')->type('equals')->target($OriginalPassword, false)
```

&nbsp;

### 設置種類的簡寫

在你使用簡寫的時候，可能會發現有些函式與 PHP 內建的重複，但這是不會發生問題的。

| 種類英文   |   簡短    |            簡寫            | 
| ---------- | --------- | -------------------------- | 
| length     | 長度      |  `length(min, max)`        |
| range      | 範圍      |  `range(min, max)`         |
| date       | 日期      |  `date()`                  |
| in         | 清單      |  `inside(list)`            |
| email      | 電郵      |  `email()`                 | 
| gender     | 性別      |  `gender()`                | 
| ip         | IP        |  `ip()`                    | 
| ipv4       | IPv4      |  `ipv4()`                  |
| ipv6       | IPv6      |  `ipv6()`                  | 
| url        | 網址      |  `url(urlNot)`             |
| equals     | 相同      |  `equals(target, isField)` |

&nbsp;

當你使用簡寫，`type()` 是不必要的。

```php
->add('username')->length(3, 12)

->add('age')->range(1, 99)

->add('url')->url()

->add('passwordConfirm')->equals($Password, false)
```

&nbsp;

## 設置附加功能

附加功能例如「必填項目」，或者是限制內容的格式，例如:「a-Z0-9」，

有的附加功能支援縮寫，而所有的的附加功能都可以用在縮寫的種類上。

|     函式     |      簡短     |   簡寫    | 
| ------------ | ------------- | --------- | 
| `required()` | 必填          |  `req()`  |
| `format()`   | 格式          |           |
| `trim()`     | 清除字尾空白  |           |     

&nbsp;

##### `required()` 或 `req()` 的用法

這會讓某個欄位成為必填項目，如果**內容只有空白**，或是**完全沒內容**，就會無法通過，
 
兩者都是相同的東西，僅是一個縮寫而已。

&nbsp;

```php
/** 可以用在縮寫種類上 */
->add('age')->range(1, 99)->req()

/** 如果你想要讓他完整一點也可以 */
->add('url')->type('url')->required()
```

&nbsp;

##### `format()` 的用法

這會限制內容必須是什麼格式，這個的用法為 [正規表達式](https://en.wikipedia.org/wiki/Regular_expression)。

```php
->add('username')->length(1, 12)->format('a-Z0-9')
```

&nbsp;

##### `trim()` 的用法

移除字尾最後的空白。

```php
->add('address')->length(7, 60)->trim()
```

&nbsp;

## 驗證

一旦你完成了先前的設置，就可以透過 `check()` 或 `loadCheck()` 來進行驗證手續。

```php
$jaji->check();

/** 如果你要讀取設定並驗證的話則是這樣 */
$jaji->loadCheck($myRule);
```

&nbsp;

## 取得驗證後的資料

驗證後的資料可能會比較安全一點_（我是說，你網站最終被駭入，還是不應該找我，對吧？）_

你可以透過 `safe` 來取得一個被驗證後的資料。

**請注意：如果這次的驗證是錯誤的，那麼你將會取得到一個空白的陣列，**

**最好的方法是在驗證錯誤的時候就中止繼續。**

```php
/** 先驗證 */
$jaji->check();

/** 然後把這個拿來做後續資料處理 */
$safe = $jaji->safe;

/** 像這樣 */
foobar($safe['username']);
```

&nbsp;

## 儲存成預先規則

一旦你完成了設置，你可以選擇不要檢查（也就是不要驗證），而是使用下列方式儲存在一個變數中。

**如果你決定要儲存成規則，那麼就請不要指定來源，因為那是無用的。**

```php
$myRule = $jaji->save()
```

&nbsp;

如果你不是很清楚，我們從頭來過，最後採用儲存的方式。

```php
$myRule = $jaji->add('username')->type('length')->min(3)->max(32)->required()->format('a-Z0-9')
               ->add('age')     ->type('range') ->min(7)->max(99)->required()
               ->add('ip')      ->type('ip');
               ->save();
```

&nbsp;

接著 `$myRule` 內就會有一串你剛所儲存的規則，若要使用，請在下次這樣使用：

```php
$jaji->source($_POST)
     ->loadCheck($myRule);
```

如此一來，就會以剛才的規則去檢查 $_POST。

&nbsp;

## 跨站表單驗證（可選）

跨站表單通常是被建議開啟的，無論做什麼，

**風紀委員都建議你不要將這個功能關閉，直至你有另一個跨站表單的檢查功能。**

&nbsp;

##### 原理

風紀委員對跨站表單驗證的處理方式是，先在你第一次進入這個網站的時候，

**生成一個隨機字串**，如此一來只有那個使用者知道該字串，

接下來的表單欄位或是請求標頭，都至少需要含有這個字串，否則風紀委員都會拒絕接受。

&nbsp;

##### 必須需求

你的表單或是來源標頭，**至少要有一個地方含有用以檢查是否正常送出的「麵包屑（Crumb）」**，

你可以**透過稍候提供的方式來將麵包屑包含在你的表單中**，

或是**放置在請求標頭**，否則風紀委員都會**拒絕接受**。

&nbsp;

##### 開關跨站表單檢查

跨站表單預設是開啟的，想要關閉，將下列設置為 False：

```php
$jaji->csrf = false;
```

&nbsp;

##### 名稱設定

你應該要記得這些名稱，這樣你才能夠正確的進行跨站表單驗證，

你可以自訂用來檢查的那些欄位名稱（**下面這些都是預設值，當然你可以改變他們**）：

```php
/** 儲存在 Cookie 中的麵包屑名稱 */
$jaji->csrfCookieName = 'jajjimento_token';

/** 你的表單麵包屑欄位名稱（放在表單內的欄位） */
$jaji->csrfFieldName  = 'jajjimento_token';

/** 儲存在 $_SESSION 中的的加密 Token 名稱 */
$jaji->csrfName       = 'jajjimentoToken';

/** 放置麵包屑的自訂標頭名稱 */
$jaji->csrfHeaderName = 'X-CSRF-TOKEN';
```

&nbsp;

##### 取得麵包屑內容

透過「名稱設定」的地方，你可以很輕易透過那些變數，取得欄位名稱，

倘若你要取得**麵包屑內容**，則可以透過下列函式。

```php
$jaji->getCrumbValue();
```

&nbsp;

##### 插入驗證欄位

為了縮短你的困擾，用下列方式可以直接將一個隱藏欄位插入你的表單。

```php
$jaji->insertCrumb();

/** 例如 */
<form>
    <?= $jaji->insertCrumb(); ?>
</form>
```

&nbsp;

##### 關於 XHR（AJAX）與標頭

每次都要在送出資料時，特地為麵包屑新增一個欄位太麻煩了（當然你也可以這樣做。），

我們會建議你直接將麵包屑設置在全域請求標頭中，

別忘記，**如果你在上方教學更改了他們的名稱，請記得也更改你 JavaScript 中的名稱**。

假設 jQuery 和一個 $.Cookie 插件：

```javascript
/** AJAX 全域設定，如此一來你就不需要替每一個 AJAX 都手動設置標頭 */
$.ajaxSetup(
{
    beforeSend: function(xhr)
    {
        /** 設置一個名為 "X-CSRF-TOKEN" 的標頭，而內容是來自名為 "jajjimento_token" 的 Cookie */
        xhr.setRequestHeader("X-CSRF-TOKEN", $.cookie("jajjimento_token"));
    }
});
```

&nbsp;


## 當「艾拉」存在的時候

如果你有 [艾拉](http://github.com/TeaMeow/Aira) 來協助你處理錯誤，

那麼你應該將此這為 True（預設為 False。）。

```php
$jaji->hasAira = true;
```

接下來， `aira::add('INCORRECT_FORM')` 將會在驗證失敗時被呼叫。


