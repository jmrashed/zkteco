<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class AttendanceSanitizedTest extends TestCase
{
    /**
     * Attendance::getSanitized() talks to a real device socket via get(),
     * so exercise the isPlausibleRecord() filter directly against the exact
     * corrupted SpeedFace-V5L output reported in issue #7, since that's the
     * data this filter needs to reject.
     */
    private function isPlausibleRecord(array $record): bool
    {
        $reflection = new \ReflectionClass(\Jmrashed\Zkteco\Lib\Helper\Attendance::class);
        $method = $reflection->getMethod('isPlausibleRecord');

        return $method->invoke(null, $record);
    }

    public function testKeepsTheCorrectlyParsedRecord()
    {
        $record = ['uid' => 54, 'id' => 'NB2401010', 'state' => 4, 'timestamp' => '2024-11-28 16:43:25', 'type' => 1];

        $this->assertTrue($this->isPlausibleRecord($record));
    }

    public function testRejectsZeroUidRecord()
    {
        $record = ['uid' => 0, 'id' => "\xff2", 'state' => 48, 'timestamp' => '2000-01-01 03:29:54', 'type' => 0];

        $this->assertFalse($this->isPlausibleRecord($record));
    }

    public function testRejectsNonPrintableBadgeId()
    {
        $record = ['uid' => 1, 'id' => "\x04\xbd\x84\xb7/", 'state' => 0, 'timestamp' => '2035-02-10 22:20:41', 'type' => 49];

        $this->assertFalse($this->isPlausibleRecord($record));
    }

    public function testRejectsOutOfRangeStateOrType()
    {
        $record = ['uid' => 1, 'id' => '557', 'state' => 0, 'timestamp' => '2024-01-01 08:00:00', 'type' => 49];

        $this->assertFalse($this->isPlausibleRecord($record));
    }

    public function testRejectsEpochSentinelTimestamp()
    {
        $record = ['uid' => 13055, 'id' => '557', 'state' => 0, 'timestamp' => '2000-01-01 00:00:00', 'type' => 0];

        $this->assertFalse($this->isPlausibleRecord($record));
    }
}
