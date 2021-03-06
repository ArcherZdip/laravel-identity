# Laravel-Chinese-IDNumber
<a href="https://github.com/ArcherZdip/laravel-identity/stargazers"><img alt="GitHub stars" src="https://img.shields.io/github/stars/ArcherZdip/laravel-identity.svg?color=green"></a>
<a href="https://github.com/ArcherZdip/laravel-identity/network"><img alt="GitHub forks" src="https://img.shields.io/github/forks/ArcherZdip/laravel-identity.svg?color=green"></a>
[![Build Status](https://travis-ci.org/ArcherZdip/laravel-identity.svg?branch=master)](https://travis-ci.org/ArcherZdip/laravel-identity)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArcherZdip/laravel-identity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArcherZdip/laravel-identity/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ArcherZdip/laravel-identity/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ArcherZdip/laravel-identity/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/ArcherZdip/laravel-identity/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

**生成和验证中国居民身份证号码**

## Installation
通过在composer.json中配置安装：
```php
composer require archerzdip/laravel-identity:dev-master
```
or
```php
// composer.json
"archerzdip/laravel-identity":"dev-master"
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
$bool = identity_verity(123456);  // false
// or
use ArcherZdip\Identity\VerityChineseIDNumber;

$bool = VerityChineseIDNumber::isValid(string $idNumber);
```

**其他功能：**
```php
use ArcherZdip\Identity\VerityChineseIDNumber;
// 获取生日
$birthday = (new VerityChinsesIDNumber(string $idNumber))->getBirthday()->format('Y-m-d');

// 获取年龄
$age = (new VerityChinsesIDNumber(string $idNumber))->getAge();

// 是否为男性
$isMale = (new VerityChinsesIDNumber(string $idNumber))->isMale();

// 是否为女性
$isFemale = (new VerityChinsesIDNumber(string $idNumber))->isFemale();

// 获取年份
$year = (new VerityChinsesIDNumber(string $idNumber))->getYear()

// 获取月份
$month = (new VerityChinsesIDNumber(string $idNumber))->getmonth()

// 获取日期
$day = (new VerityChinsesIDNumber(string $idNumber))->getday()
```

## Console
可以使用console来获取一个或者多个ID：
```
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


也可以使用console来验证ID是否有效：
```
$ php artisan identity:verity --help            
Usage:
  identity:verity <idnumber>

Arguments:
  idnumber              Chinese ID number string

```


## 后续
身份证号码中携带附加信息，如地区信息、生日、性别等。