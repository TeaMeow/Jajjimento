&nbsp;

<p align="center">
  <img src="http://i.imgur.com/QqGiSvi.png"/>
</p>
<p align="center">
  <i>Stop it when it's false.</i>
</p>

&nbsp;


# Jajjimento

[**這份說明的原文是：正體中文，你可以在這裡觀看這份文件的中文版本。**](中文說明.md)

**Any pull request about to fix the english translation of this document are welcomed ; )**

Jajjimento (ジャッジメント) means judgement, this is a form validation class based on PHP,

It's dead simple to use, you can even save your rules and use it later with few lines only,

the big difference between the previous version is OOP, and anti-CSRF is now supported.

&nbsp;

## Features

1. You can save the rules and use it in the futrue.

2. Easy and simple.

3. Can validate an array, or just a variable.

4. Semantic function names with less letters.

5. Aira supported.

6. Anti-CSRF.

&nbsp;

## Index

1. [Example](#example)

2. [Set the source or load the rules](#set-the-source-or-load-the-rules)

  * [Source mode](#source-mode)
  * [Manual mode](#manual-mode)
  * [Load the rules](#load-the-rules)

3. [Set type](#set-type)

  * [`min()` and `max()`](#min-and-max)
  * [`dateFormat()`](#dateformat)
  * [`inside()`](#inside)
  * [`urlNot()`](#urlnot)
  * [`target()`](#target)
  * [Shorthands](#Shorthands)

4. [Set extra options](#set-extra-options)

  * [`required()` and `req()`](#required-and-req)
  * [`format()`](#format)
  * [`trim()`](#trim)

5. [Validate](#validate)

6. [Get the data after validated](#get-the-data-after-validated)

7. [Save the rules](#save-the-rules)

8. [Anti-CSRF (Optional)](#anti-csrf-optional)

  * [How it works](#how-it-works)
  * [Requirement](#requirement)
  * [Enable the anti-CSRF](#enable-the-anti-csrf)
  * [Cutomize the names](#cutomize-the-names)
  * [Get the value of the crumb](#get-the-value-of-the-crumb)
  * [Insert a hidden field](#insert-a-hidden-field)
  * [About XHR (AJAX) and the header](#about-xhr-ajax-and-the-header)

9. [When Aira does exist](#when-aira-does-exist)

&nbsp;

## Example

Many people would face with thier $_POST forms, 

and here's how you deal with it with Jajjimento:

```php
/** Set $_POST as the source */
$jaji->source($_POST)

     /** We will teach you how to set a rule later */
     ->add('username')->type('length')->min(3)->max(16)->required()
     
     /** And shorhands are allowed :D */
     ->add('username')->length(3, 16)->req()

     /** Let's rock'n roll once you done all the settings */
     ->check();
```

&nbsp;

## Set the source or load the rules

You have two choices between set the source or load the rules.

And here's two way to set the source:

* Source mode － It can be a `$_POST` or `$_GET`, and even an *array*。
* Manual mode － You should use manual mode when your source is a variable not an array.

&nbsp;

#### Source mode

You should use `source()` to enable the source mode if you are going to validate an array.

Here's a example about validate a $_POST array.

```php
$jaji->source($_POST)
     ->add('Field Name')   // We will validate $_POST['username'] if the field name is "username".
```

&nbsp;

#### Manual mode

If you want to validate a variable not an array,

**You won't need to use `source()`, just add the rules.**

```php
$jaji->add($Test)   // In this situation, $Test became the target we will vaildate.
```

&nbsp;

#### Load the rules

You can use `loadCheck()` to apply the rules and it will process the validation directly, 

we will teach you how to save the rules later.

```php
$jaji->source($_POST)
     ->loadCheck($rule)   // And it will apply the rules from $Rule and process the validation.
```

&nbsp;

## Set type

Use `type()` to set the type of the rule, 

if you think it's too long for you to handle (Yep.), **you can use shorthands**.

And here's the types that you can set with:

|    Name    |     Depend with      |                                               Description                                                  |
| ---------- | -------------------- | ---------------------------------------------------------------------------------------------------------- |
| length     |  `min()`, `max()`    | String Length; The length of the string must shorter than `max()` and longer than `min()` .                |
| range      |  `min()`, `max()`    | Number Range; The number must smaller than `max()` and bigger than `min()`.                             |
| date       |  `dateFormat()`      | Date Format; The date format must same as the format where you setted by `dateFormat()`. ex: `YYYY-MM-DD`. |
| in         |  `inside()`          | Is in; The value must in the array which you setted by `inside()`.                                         |
| email      |                      | Email; It must be an valid email address.                                                                  |
| gender     |                      | Gender; Gender must be one of the f(emale) or m(ale) or o(ther).                                           |
| ip         |                      | All IP; It must be a valid IPv4 or IPv6 address.                                                           |
| ipv4       |                      | IPv4; It must be a valid IPv4 address.                                                                     |
| ipv6       |                      | IPv6; It must be a valid IPv6 address.                                                                     |
| url        |  `urlNot()`          | Ur；It must be a url address and use `urlNot()` too add the urls which you don't allow.                    |
| equals     |  `target()`          | Equals; Content must be same as the content of the `target()`.                                             |

&nbsp;

#### `min()` and `max()`

The length or the number must longer or bigger than `min()` and shorter or smaller than `max()`.

```php
->add('username')->type('length')->min(3)->max(6)
```

&nbsp;

#### `dateFormat()`

`dateFormat()` is the fucntion that you can set the date formats you allowed,

It can be a single one, or you can pass an array with many formats in it, the format is following [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601),

In a nutshell, just something like 'YYYY-mm-dd' or 'dd/mm/YYYY'.

```php
->add('username')->type('date')->dateFormat('YYYY-mm-dd')

/** Or */
->add('birthday')->type('date')->dateFormat(['YYYY-mm-dd', 'mm/dd/YYYY'])
```

&nbsp;

#### `inside()`

The value must in the array which we setted by `inside()`.

```php
->add('options')->type('in')->inside(['A', 'B', 'C', 'D'])
```

&nbsp;

#### `urlNot()`

Use the function to set those url you don't allow. 

ex: `http` or `https`, it can be a string or an array with strings.

```php
->add('url')->type('url')->urlNot('ftp')

/** Or */
->add('url')->type('url')->urlNot(['http', 'https'])

/** Even */
->add('url')->type('url')->urlNot(['http://www.google.com/', 'http://www.yahoo.com/'])
```

&nbsp;

#### `target()`

The value must same as the content which you setted by `target()`, and you should notice that:

**No matter you're in the source mode or manual mode,**

**you should pass an field name to `target()` instead of a variable.**

**But if you want to pass a variable, you can just set the SECOND paramemter as false.**

```php
->add('passwordConfirm')->type('equals')->target('password')

/** If you are going to pass a variable, set the second paramemter as false */
->add('passwordConfirm')->type('equals')->target($OriginalPassword, false)
```

&nbsp;

### Shorthands

You might have noticed that some function names are the same as the php built-in functions,

but it's alright, we have already avoided that problem.

|    Name    |          Shorhand         | 
| ---------- | ------------------------- | 
| length     | `length(min, max)`        |
| range      | `range(min, max)`         |
| date       | `date()`                  |
| in         | `inside(list)`            |
| email      | `email()`                 | 
| gender     | `gender()`                | 
| ip         | `ip()`                    | 
| ipv4       | `ipv4()`                  |
| ipv6       | `ipv6()`                  | 
| url        | `url(urlNot)`             |
| equals     | `equals(target, isField)` |

&nbsp;

`type()` is not required when you using shorhands.

```php
->add('username')->length(3, 12)

->add('age')->range(1, 99)

->add('url')->url()

->add('passwordConfirm')->equals($Password, false)
```

&nbsp;

## Set extra options

Some extra options like "required", or the format of the string like "A-Z0-9",

and some of them got shorhand either, all of the extra options can be used on the shorthanded types.

|   Function   |                  Description                   | Shorthand | 
| ------------ | ---------------------------------------------- | --------- | 
| `required()` | Requird                                        |  `req()`  |
| `format()`   | Format with RegEx                              |           |
| `trim()`     | Remove the whitespace at the end of the string |           |     

&nbsp;

#### `required()` and `req()`

It will make the field become required,

and it won't be passed if the field has **empty string only** or **totally nothing**.
 
Both of the functions are the same thing.

&nbsp;

```php
/** Can use it with the shorthanded types */
->add('age')->range(1, 99)->req()

/** Or you hate shorthands */
->add('url')->type('url')->required()
```

&nbsp;

#### `format()`

It will limit the format of the string, it uses [RegEx](https://en.wikipedia.org/wiki/Regular_expression) to check.

```php
->add('username')->length(1, 12)->format('a-Z0-9')
```

&nbsp;

#### `trim()`

Remove the whitespace at the end of the string.

```php
->add('address')->length(7, 60)->trim()
```

&nbsp;

## Validate

You can process the validation with `check()` or `loadCheck()` once you done all the settings.

```php
$jaji->check();

/** Or you want to load the rules which you setted before */
$jaji->loadCheck($myRule);
```

&nbsp;

## Get the data after validated

It might be more safer to use the data which we validated (I mean, you can't blame me if you still get hacked, right? )

You can use `safe` to get the data which we validated.

**HEY: You will get an empty array if the validation was not passed,**

**the best way is stop it when the validation was not passed.**

```php
/** Validate it first */
$jaji->check();

/** And you can use this instead of the original array */
$safe = $jaji->safe;

/** Like this */
foobar($safe['username']);
```

&nbsp;

## Save the rules

Once you done the settings, you can choose to save them instead of validaing them.

**Don't set the source becuase that's useless if you decided to save them.**

```php
$myRule = $jaji->save()
```

&nbsp;

If you still don't understand, let's start from the beginning and save it at the end.

```php
$myRule = $jaji->add('username')->type('length')->min(3)->max(32)->required()->format('a-Z0-9')
               ->add('age')     ->type('range') ->min(7)->max(99)->required()
               ->add('ip')      ->type('ip');
               ->save();
```

&nbsp;

And there will be an array with rules in `$myRule`, if you want to use them, do this next time:

```php
$jaji->source($_POST)
     ->loadCheck($myRule);
```

So you can process the validation with those rules which you saved.

&nbsp;

## Anti-CSRF (Optional)

So, we highly recommend that you should enable this feature no matter what,

**You shouldn't disable this unless you have another library to handle the csrf attack.**

&nbsp;

#### How it works

A token will be generated when you entered the website,

and every forms and headers should include the token, if not, Jajjimento will refuse all the request.

&nbsp;

#### Requirement

**You can choose to put the token in the form or the header, but one of them at least**,

You can use the function which we will teach you later **to put the token in your forms**,

or **put it in the header**, **ny request will be refused if you don't do this**.

&nbsp;

#### Enable the anti-CSRF

Anti-CSRF is disabled by default, to enable it, set this as true:

```php
$jaji->csrf = true;
```

&nbsp;

#### Cutomize the names

You should remember these names, so you can know how to use it later,

you can customize the names (**These are default values**):

```php
/** The name of the cookie which we stored the token */
$jaji->csrfCookieName = 'jajjimento_token';

/** The field name of the token (the field which you should put in the form) */
$jaji->csrfFieldName  = 'jajjimento_token';

/** The name of the $_SESSION which we stored the token */
$jaji->csrfName       = 'jajjimentoToken';

/** The name of the header which we used to check the token with */
$jaji->csrfHeaderName = 'X-CSRF-TOKEN';
```

&nbsp;

#### Get the value of the crumb

You can do this if you want to **get the token**.

```php
$jaji->getCrumbValue();
```

&nbsp;

#### Insert a hidden field

To not waste your time, we added the function which can insert a hidden field in your forms automanticlly.

```php
$jaji->insertCrumb();

/** In your form */
<form>
    <?= $jaji->insertCrumb(); ?>
    
    /** Or you have custom attributes */
    <?= $jaji->insertCrumb(['ng-model' => 'misaka']); ?>
</form>
```

&nbsp;

#### About XHR (AJAX) and the header

That might makes you lazy to add the token to the XHR request everytime (Or you are not lazy),

We recommend you to set the token in the global header,

don't forget to **change their name in JavaScript if you changed their name in PHP**.

For this example, we will use jQuery and a $.Cookie plugin:

```javascript
/** Set them in global setting so you won't need to set the header with each AJAX */
$.ajaxSetup(
{
    beforeSend: function(xhr)
    {
        /** Name a header with "X-CSRF-TOKEN", **/
        /** and the content is from the cookie which named "jajjimento_token" */
        xhr.setRequestHeader("X-CSRF-TOKEN", $.cookie("jajjimento_token"));
    }
});
```

&nbsp;


## When Aira does exist

If you have [Aira](http://github.com/TeaMeow/Aira) to handler your errors,

You can set this as true (false by default).

```php
$jaji->hasAira = true;
```

and, `aira::add('INCORRECT_FORM')` will be called once the validation failed.


