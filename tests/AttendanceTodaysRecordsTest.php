<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;

class AttendanceTodaysRecordsTest extends TestCase
{
    public function testGetTodaysRecordsCanBeCalledAsInstanceMethod()
    {
        $today = date('Y-m-d');

        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAttendance'])
            ->getMock();

        $zk->method('getAttendance')->willReturn([
            ['uid' => 1, 'id' => 'A1', 'state' => 1, 'timestamp' => $today . ' 08:00:00', 'type' => 0],
            ['uid' => 2, 'id' => 'A2', 'state' => 1, 'timestamp' => '2000-01-01 08:00:00', 'type' => 0],
        ]);

        // Previously getTodaysRecords() was declared `static function getTodaysRecords(ZKTeco $self)`
        // which made calling it as `$zk->getTodaysRecords()` (per the documented usage) fatal
        // with "Too few arguments" / undefined method self::get(). This must not throw.
        $result = $zk->getTodaysRecords();

        $this->assertCount(1, $result);
        $this->assertEquals(1, reset($result)['uid']);
    }
}
