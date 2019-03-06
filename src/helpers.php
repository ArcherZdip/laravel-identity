<?php
/**
 * Created by PhpStorm.
 * User: zhanglingyu
 * Date: 2019-03-06
 * Time: 10:55
 */

if (!function_exists('identity')) {
    /**
     * Get Identity value
     *
     * @param int $limit
     * @return mixed
     */
    function identity($limit = 1)
    {
        if ($limit == 1) {
            return app('identity_faker')->one();
        }

        return app('identity_faker')->limit($limit)->get();
    }
}


if(!function_exists('identity_verity')) {
    /**
     * check chinese id number
     *
     * @param null $idNumer
     * @return bool
     */
    function identity_verity($idNumer = null)
    {
        if (is_null($idNumer)) {
            return false;
        }

        return \ArcherZdip\Identity\VerityChineseIDNumber::isValid($idNumer);
    }
}

