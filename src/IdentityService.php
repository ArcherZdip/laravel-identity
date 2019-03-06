<?php
/**
 * Created by PhpStorm.
 * User: zhanglingyu
 * Date: 2019-03-06
 * Time: 10:57
 */

namespace ArcherZdip\Identity;

class IdentityService
{
    /** @var array $attributes */
    protected $attributes = [];

    /** @var array $citys 中国城市列表 */
    protected $citys = [];

    /** @var array $provinces 中国省份列表 */
    protected $provinces = [];

    /** @var array $fillable set param */
    protected $fillable = ['province', 'birth', 'sex']; //'city', 'region',

    /** @var $limit */
    protected $limit;

    /** @var int 男 */
    const MALE = 0;

    /** @var int 女 */
    const FEMALE = 1;

    /** @var int max limit */
    const MAXCOUNT = 100;

    /**
     * Get one chinese id number
     *
     * @return mixed
     * @throws \Exception
     */
    public function one()
    {
        return collect($this->generate())->first();
    }

    /**
     * Get multiterm chinese id number.
     *
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function get()
    {
        $ids = [];
        for ($i = 0; $i < $this->getLimit(); $i++) {
            $ids[] = $this->generate();
        }

        return collect($ids);
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $limit = (int)$limit ?: 1;
        $limit = ($limit >= self::MAXCOUNT) ? self::MAXCOUNT : $limit;
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generate()
    {
        $cityId = $this->calcCityId();
        $birth = $this->calcBirth();
        $sex = $this->calcSex();

        // random number
        $suffix_a = mt_rand(0, 9);
        $suffix_b = mt_rand(0, 9);

        $base = $cityId . $birth . $suffix_a . $suffix_b . $sex;

        $idNumber = $base . $this->calcSuffixD($base);
        return $idNumber;
    }

    /**
     * calc province
     *
     * @return mixed
     */
    protected function calcProvince()
    {
        $province = $this->getProvinces();
        // set province
        if (!isset($this->attributes['province']) || !in_array($this->attributes['province'], $province->toArray(), true)) {
            return $province->random();
        }

        return $this->attributes['province'];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function calcCityId()
    {
        $province = $this->getProvince();
        $list = $this->getCitys()[$province];
        if (collect(['澳门特别行政区', '香港特别行政区', '台湾省'])->contains($province)) {
            return $list[0]['cityid'];
        }

        $randomId = random_int(1, count($list) - 1);

        $cityIdList = array_values($list[$randomId])[0];

        $randomCid = random_int(0, count($cityIdList) - 1);

        return $cityIdList[$randomCid]['cityid'];
    }

    /**
     * Calc sex
     * @return $this
     * @throws \Exception
     */
    protected function calcSex()
    {
        $sex = $this->getSex();
        // sex is null , random 1 - 8
        if ($sex === false) {
            $sex = random_int(1, 8);
        } // sex is male
        elseif ($sex == self::MALE) {
            $sex = 2 * random_int(1, 4) - 1;
        } // sex is female
        else {
            $sex = 2 * random_int(1, 4);
        }

        return $sex;
    }

    /**
     * Get timedate
     * param datetime format xxxx-xx-xx
     * @return false|string $datetime
     */
    protected function calcBirth()
    {
        $birth = $this->getBirth();
        //random Datatime
        if ($birth === false) {
            $startDate = mktime(0, 0, 0, 1, 1, 1950);
            $year = date('Y');
            $month = date('m');
            $day = date('d');
            $endDate = mktime(0, 0, 0, $month, $day, $year);
            $birth = mt_rand($startDate, $endDate);
            $datetime = date('Ymd', $birth);
        } else {
            list($year, $month, $day) = explode('-', $birth);
            if (!checkdate($month, $day, $year)) {
                die('Invalided datetime');
            }
            $datetime = $year . $month . $day;
        }

        return $datetime;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    protected function setAttributes($key, $value)
    {
        if ($this->isFillable($key)) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    protected function isFillable($key)
    {
        if (in_array($key, $this->getFillable())) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getFillable()
    {
        return $this->fillable;
    }

    /**
     * @return mixed
     */
    protected function getProvince()
    {
        return $this->calcProvince();
    }

    /**
     * @return mixed
     */
    protected function getSex()
    {
        return isset($this->attributes['sex']) ? $this->attributes['sex'] : false;
    }

    /**
     * @return mixed
     */
    protected function getBirth()
    {
        return isset($this->attributes['birth']) ? $this->attributes['birth']: false;
    }

    /**
     * @return mixed
     */
    protected function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param $name
     * @param $arguments
     * @return IdentityService
     */
    public function __call($name, $arguments)
    {
        if (!is_null($arguments) && isset($arguments[0]) ) {
            $this->setAttributes($name, $arguments[0]);
        }
        return $this;
    }

    /**
     * @return array
     */
    protected function getCitys()
    {
        $this->loadCitys();

        return $this->citys;
    }

    /**
     * @return array
     */
    protected function getProvinces()
    {
        $this->loadProvinces();

        return $this->provinces;
    }

    /**
     * Get the provinces from json file
     * @return $this
     */
    protected function loadProvinces()
    {
        //Get the provinces from json file
        if (sizeof($this->provinces) == 0) {
            $this->provinces = collect(json_decode(file_get_contents(__DIR__ . '/data/provinces.json'), true));
        }
        return $this;
    }

    /**
     * Get citys from Json file
     * @return $this
     */
    protected function loadCitys()
    {
        //Get the citys from the JSON file
        if (sizeof($this->citys) == 0) {
            $this->citys = collect(json_decode(file_get_contents(__DIR__ . '/data/citys.json'), true));
        }

        //Return the citys
        return $this;
    }

    /**
     * calc chinese id number last word
     * @param $base
     * @return string
     */
    protected function calcSuffixD($base)
    {
        if (strlen($base) <> 17) {
            die('Invalid Length');
        }
        // 权重
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $sums = 0;
        for ($i = 0; $i < 17; $i++) {
            $sums += substr($base, $i, 1) * $factor[$i];
        }

        $mods = $sums % 11; //10X98765432

        switch ($mods) {
            case 0:
                return '1';
                break;
            case 1:
                return '0';
                break;
            case 2:
                return 'X';
                break;
            case 3:
                return '9';
                break;
            case 4:
                return '8';
                break;
            case 5:
                return '7';
                break;
            case 6:
                return '6';
                break;
            case 7:
                return '5';
                break;
            case 8:
                return '4';
                break;
            case 9:
                return '3';
                break;
            case 10:
                return '2';
                break;
        }
    }
}