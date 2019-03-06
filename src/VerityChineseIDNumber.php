<?php
/**
 * Created by PhpStorm.
 * User: zhanglingyu
 * Date: 2019-02-20
 * Time: 10:53
 */

namespace ArcherZdip\Identity;

use Exception;
use DateInterval;
use DateTimeImmutable;

class VerityChineseIDNumber
{
    const PATTERN = '/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{2})(\d)([0-9]|X)$/';

    /** @var string|null $idNumber */
    public $idNumber = null;

    /** @var $year */
    public $year;

    /** @var $month */
    public $month;

    /** @var $day */
    public $day;

    /** @var $sexPosition */
    public $sexPosition;

    /**
     * 真实但无效的身份证号码
     *
     * @var array
     */
    public static $trustedIdNumbers = [];

    /**
     * VerityChineseIDNumber constructor.
     * @param string $idNumber
     * @throws Exception
     */
    public function __construct(string $idNumber)
    {
        $this->idNumber = $idNumber;
        $this->parseData($this->idNumber);
    }

    /**
     * valid chinese id number.
     *
     * @param $idNumber
     * @return bool
     */
    public static function isValid($idNumber)
    {
        try {
            new static($idNumber);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * 获取生日
     *
     * @param $tz
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function getBirthday($tz = null)
    {
        $time = date('Y-m-d', mktime(0, 0, 0, $this->month, $this->day, $this->year));

        return new DateTimeImmutable($time, $tz);
    }

    /**
     * 获取年龄
     *
     * @param bool $strict 是否为严格模式
     * @return int
     * @throws \Exception
     */
    public function getAge($strict = true)
    {
        $today = (new DateTimeImmutable('today'));
        $birthday = $this->getBirthday();

        if ($strict) {
            $age = $today->diff($birthday)->y;
            if ($birthday > $today->sub(new DateInterval("P{$age}Y"))) {
                $age++;
            }
        } else {
            $age = abs((int)$today->format('Y') - (int)$birthday->format('Y'));
        }

        return $age;
    }

    /**
     * 是否为男性
     *
     * @return bool
     */
    public function isMale()
    {
        return $this->sexPosition % 2 !== 0;
    }

    /**
     * 是否为女性
     *
     * @return bool
     */
    public function isFemale()
    {
        return $this->sexPosition % 2 === 0;
    }

    /**
     * 获取年份
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * 获取月份
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * 获取日期
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $idNumber
     * @return bool
     */
    protected function verify(string $idNumber)
    {
        // 检验18位身份证的校验码是否正确。
        // 校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
        $map = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

        $sum = 0;
        for ($i = 17; $i > 0; $i--) {
            $n = pow(2, $i) % 11;
            $sum += $n * (int)$idNumber[17 - $i];
        }

        return $map[$sum % 11] === $idNumber[17];
    }

    /**
     * @param string $idNumber
     * @return $this|false
     * @throws Exception
     */
    public function parseData(string $idNumber)
    {
        if (preg_match(static::PATTERN, $idNumber, $matches)) {
            // 检查生日日期是否正确
            $birthday = $matches[2] . '/' . $matches[3] . '/' . $matches[4];

            if (strtotime($birthday)) {
                $this->year = (int)$matches[2];
                $this->month = (int)$matches[3];
                $this->day = (int)$matches[4];
                $this->sexPosition = (int)$matches[6];

                if (in_array($idNumber, static::$trustedIdNumbers, true)) {
                    return $this;
                }

                if ($this->verify($idNumber)) {
                    return $this;
                }
            }
        }

        throw new Exception('无效的身份证号码。');
    }
}