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

和以往的版本不同，這次比較偏向物件導向方式。

&nbsp;

## 特色

1. 可以儲存規則供未來重複使用。

2. 簡單明瞭。

3. 可以檢查陣列，或是直接檢查變數。

4. 更具有意義的函式名稱卻又簡短。 

5. 支援艾拉。

&nbsp;

## 索引

1. [舉例](#舉例)

2. [設置來源 或 採用預先規則](#設置來源-或-採用預先規則)

  * 來源模式
  * 手動模式
  * 採用預先規則

3. [設置種類](#設置種類)

  * [`min()` 和 `max()` 的用法](min()和max()的用法)
  * `dateFormat()` 的用法
  * `inside()` 的用法
  * `urlNot()` 的用法

4. [設置附加功能](#設置附加功能)

  * `required()` 或 `req()` 的用法
  * `format()` 的用法
  * `trim()` 的用法

5. [驗證](#驗證)

6. [儲存成預先規則](#儲存成預先規則)

7. [當「艾拉」存在的時候](#當艾拉存在的時候)

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

今天如果你並沒有陣列要檢查，而是想直接檢查一個變數，則是 `manual()`。

```php
$jaji->manual()
     ->add($Test)   // 在這個情況，$Test 會直接成為來源，該變數的內容會被檢查。
```

&nbsp;

#### 採用預先規則

你可以利用 `loadCheck()` 直接套用一個你先前儲存的規則並直接檢查，稍後會提及如何儲存一個規則。

```php
$jaji->source($_POST)
     ->loadCheck($Rule)   // 接下來會套用 $Rule 內所含有的規則並檢查。
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

### 設置種類的簡寫

在你使用簡寫的時候，可能會發現有些函式與 PHP 內建的重複，但這是不會發生問題的。

| 種類英文   |   簡短    |        簡寫          | 
| ---------- | --------- | -------------------- | 
| length     | 長度      |  `length(min, max)`  |
| range      | 範圍      |  `range(min, max)`   |
| date       | 日期      |  `date()`            |
| in         | 清單      |  `inside(list)`      |
| email      | 電郵      |  `email()`           | 
| gender     | 性別      |  `gender()`          | 
| ip         | IP        |  `ip()`              | 
| ipv4       | IPv4      |  `ipv4()`            |
| ipv6       | IPv6      |  `ipv6()`            | 
| url        | 網址      |  `url(urlNot)`       |  

&nbsp;

當你使用簡寫，`type()` 是不必要的。

```php
->add('username')->length(3, 12)

->add('age')->range(1, 99)

->add('url')->url()
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

一旦你完成了先前的設置，就可以直接這樣驗證。

```php
$jaji->check();

/** 如果你要讀取設定並驗證的話則是這樣 */
$jaji->loadCheck($myRule);
```

&nbsp;

## 儲存成預先規則

一旦你完成了設置，你可以選擇不要檢查（也就是不要驗證），而是使用下列方式儲存在一個變數中。

**如果你決定要儲存成規則，那麼就請不要指定來源（`source()` 或 `manual()`），因為那是無用的。**

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

## 當「艾拉」存在的時候

如果你有 [艾拉](http://github.com/TeaMeow/Aira) 來協助你處理錯誤，

那麼你應該將此這為 True（預設為 False。）。

```php
$jaji->hasAira = true;
```

接下來， `aira::add('INCORRECT_FORM')` 將會在驗證失敗時被呼叫。


