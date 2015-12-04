# Jajjimento

Jajjimento (ジャッジメント) meaning Judgement in japanese,

this is a form validation for PHP, and it's easy to use,

also supports the *pre-rules*, so you can use the same rule no need to set it twice.

**And the false will be returned if failed on the validation.**

# Usage

Jajjimento is a class that you need to construct it first.

```php
/** If you have no pre-rules */
new Jajjimento();

/** Set the three arguments if you have pre-rules, we'll talk about it later */
new Jajjimento($_POST, 'User', 'Create');
```

# Example

To validate a $_POST['Username'] with the length limit.

```php
$Jajjimento->Source($_POST)
           ->Add('Username', 'Length', true, 3, 30, 'a-Z0-9')
           ->Check();
```

## First - Set the source or not.

The source can be `$_POST` or `$_GET` or an *array*,

if you have a dynmanic or a unknown source, you can just set it as the *manual*.

```php
/** Set a source */
$Jajjimento->Source($_POST);

/** Or set it as manual*/
$Jajjimento->Manual();
```

## Second - Add the rules.

It's time to add your rules about how should Jajjimento check the source.

```php
$Jajjimento->Add($Field, $Type, $Required, $Min, $Max=null, $StringFormat=null, $Trim=true)
```

### $Field

A field should be the `key` which is in the array if you setted a source,

or it will be used as a source directly if you setted manual before.

```php
/** Use it like this if you setted a source */
$Jajjimento->Add('Username' ..

/** Or as a source if you setted as manual mode */
$Jajjimento->Add($TheValueShouldCheck ..
```

### $Type

The type of the validation of this rule, here's the list.
                
1.**Length**
 
*(`$Min` and `$Max` will now be the limit of the length of the string.)*

2.**Range**

*(`$Min` and `$Max` will now be the range for the numbers.)*

3.**Date+**
 
*(`$Min` is now be the format of the date like `YYYY-MM-DD` or `MM-DD-YYYY`.)*

4.**Switch++**

*(`$Min` should be an array now, and the value should be in this array.)*

5.**Email++**

6.**Gender++**

*(o(ther) and m(ale) and f(emal) accept only.)*

7.**IP++**

8.**URL++**

***+: `$Max` will not be availabled in this type.***

***++: `$Min` and `$Max` will not be availabled in this type.***

```php
$Jajjimento->Add('Username', 'Date' ..
```

### $Require

Set true if it's required. **It's high priority.**

```php
$Jajjimento->Add('Username', 'Date', true ..
```

### $StringFormat

Used to limit the format of the string.

1. a-Z
2. A-Z
3. 0-9
4. a-Z0-9
5. A-Z0-9
6. a-Z0-9~
7. Lang~
8. Lang


```php
$Jajjimento->Add('Username', 'Date', true, 'a-Z' ..
```

### $Trim

Remove the spaces at the end.

```php
$Jajjimento->Add('Username', 'Date', true, 'a-Z', .. true);
```

## Third - Validate it.

Once you all done, it's time to validate it like this.

```php
$Jajjimento->Check();

/** Or you are using pre-rules */
$Jajjimento->PreCheck('MAIN_TYPE', 'SUB_TYPE');
```

## When Aira does exist

If you have [Aira](http://github.com/TeaMeow/Aira) to handler your errors,

You should set this to true (false by default.).

```php
$Jajjimento->HasAira = true;
```

and `Aira::Add('INCORRECT_FORM')` will be called once the validation failed.

## Use Pre-Rules

Pre-Rules if designed for those people who might use the same rule again and again,

it's in the `jajjimento-rules/rules.php`, and it basically like this,

**FIRST**, you need a main type like 'User' or 'Post'.

*SECOND*, you need a sub type for this rule like 'Create', 'Remove'.

```php
$Post = [
         'Create' => [
                        ['Content', 'Length', true, 1, 65535]
                     ]
        ];

$User = [
         'Remove' => [
                        ['Username', 'Length', true, 1, 30],
                        ['Password', 'Length', true, 4, 80]
                     ]
        ];
```

Next time you call Jajjimento can just like this.

```php
new Jajjimento($_POST, 'User', 'Remove');
```

And it will require the rule which you setted before.
