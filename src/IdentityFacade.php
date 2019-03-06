<?php
/**
 * Created by PhpStorm.
 * User: zhanglingyu
 * Date: 2019-03-06
 * Time: 10:59
 */
namespace ArcherZdip\Identity;

class IdentityFacade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'identity_faker';
    }
}