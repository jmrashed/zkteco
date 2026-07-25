<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\Util;

class GroupTest extends TestCase
{
    public function testSetUserGroupEncodesUidLowHighByteThenGroupId()
    {
        $captured = null;

        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturnCallback(function ($command, $string) use (&$captured) {
            $captured = [$command, $string];
            return true;
        });

        $result = $zk->setUserGroup(300, 5);

        $this->assertTrue($result);
        $this->assertEquals(Util::CMD_USERGRP_WRQ, $captured[0]);
        // uid 300 = 0x012C -> low byte 0x2C, high byte 0x01, then group id 5
        $this->assertEquals(chr(0x2C) . chr(0x01) . chr(5), $captured[1]);
    }

    public function testSetUserGroupRejectsOutOfRangeValues()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();
        $zk->expects($this->never())->method('_command');

        $this->assertFalse($zk->setUserGroup(0, 5));
        $this->assertFalse($zk->setUserGroup(1, 256));
    }

    public function testGetUserGroupParsesGroupIdFromReply()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturn(chr(7));

        $this->assertEquals(7, $zk->getUserGroup(1));
    }

    public function testGetUserGroupReturnsFalseWhenCommandFails()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturn(false);

        $this->assertFalse($zk->getUserGroup(1));
    }

    public function testSetGroupTimezonesPadsToThreeSlots()
    {
        $captured = null;

        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturnCallback(function ($command, $string) use (&$captured) {
            $captured = [$command, $string];
            return true;
        });

        $zk->setGroupTimezones(2, [1]);

        $this->assertEquals(Util::CMD_GRPTZ_WRQ, $captured[0]);
        // group id 2, timezone 1 (2 bytes LE), then two empty (0) timezone slots
        $this->assertEquals(chr(2) . chr(1) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0), $captured[1]);
    }

    public function testGetGroupTimezonesParsesThreeTimezoneIds()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturn(chr(1) . chr(0) . chr(2) . chr(0) . chr(3) . chr(0));

        $this->assertEquals([1, 2, 3], $zk->getGroupTimezones(2));
    }
}
