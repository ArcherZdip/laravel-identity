<?php
/**
 * Created by PhpStorm.
 * User: zhanglingyu
 * Date: 2019-03-06
 * Time: 11:01
 */

namespace ArcherZdip\Identity;

use Tests\TestCase;

class IdentityTest extends TestCase
{


    /** @test */
    public function testOne()
    {
        $id = app('identity_faker')->sex('男')->province()->birth('2018-01-02')->one();

        $this->assertTrue(VerityChineseIDNumber::isValid($id));
    }

    /** @test */
    public function testGet()
    {
        $ids = app('identity_faker')->birth('2018-12-12')->limit(10)->get();

        $this->assertEquals(10, count($ids));

        foreach ($ids as $v) {
            $this->assertTrue(VerityChineseIDNumber::isValid($v));
        }
    }

    /** @test */
    public function testIdentityVerity()
    {
        $id1 = '123456';
        $this->assertFalse(identity_verity($id1));

        $id2 = identity();
        $this->assertTrue(identity_verity($id2));
    }
}