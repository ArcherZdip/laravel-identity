# Laravel-Chinese-IDNumber
**生成和验证中国居民身份证号码**

## Installation
You can install the package via composer:
```php
composer require archerzdip/laravel-chinese-idnumber
```
or
```php
// composer.json
"archerzdip/laravel-chinese-idnumber":"dev-master"
// composer update
composer update
```
### Publish
By running 
`php artisan vendor:publish --provider="ArcherZdip\Identity\IdentityServiceProvice"` in your project all files for this package will be published. 

## Usage
**可以使用`identity()`帮助函数,或者`app('identity_faker')`来获取身份证号码 :**
```php

// Get one id number value
identity();
app('identity_faker')->one();

// Get multiple id number value
identity(10);
app('identity_faker')->limit(10)->get();

// 可以设置省份、性别、生日来获取特定身份证号码
app('identity_faker')->province('北京市')->sex('男')->birth('2018-02-09')->one();


```

**可以使用`identity_verity()`来验证身份号码的有效性:**

```php
identity_verity(123456);  // false
// or
ArcherZdip\Identity\VerityChineseIDNumber::isValid($idNumer);
```

## Console
It is also possible to get id number within the console:
```php
php artisan identity:set

$ php artisan identity:get --help                   
Usage:
  identity:get [options]

Options:
  -l, --limit[=LIMIT]        Get identity number
  -P, --province[=PROVINCE]  Set province,like `北京市`
  -S, --sex[=SEX]            Set Sex,like `男`
  -B, --birth[=BIRTH]        Set birth,like xxxx-xx-xx

```
可以使用-l 指定获取条数，-P 指定省份，-S 指定性别，-B 指定生日


And it is also possible to verity id number within the console:
```php
php artisan identity:verity {idnumer}

$ php artisan identity:verity --help            
Usage:
  identity:verity <idnumber>

Arguments:
  idnumber              Chinese ID number string

```
