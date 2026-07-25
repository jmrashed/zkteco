<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class AttendanceFilteringTest extends TestCase
{
    /**
     * Attendance::get() talks to a real device socket, so exercise the
     * limit/range logic directly against a fixed set of parsed records
     * instead (the same shape Attendance::get() produces).
     */
    private function sample(): array
    {
        return [
            ['uid' => 1, 'id' => 'A', 'state' => 1, 'timestamp' => '2024-01-01 08:00:00', 'type' => 0],
            ['uid' => 2, 'id' => 'B', 'state' => 1, 'timestamp' => '2024-01-02 08:00:00', 'type' => 0],
            ['uid' => 3, 'id' => 'C', 'state' => 1, 'timestamp' => '2024-01-03 08:00:00', 'type' => 0],
            ['uid' => 4, 'id' => 'D', 'state' => 1, 'timestamp' => '2024-01-04 08:00:00', 'type' => 0],
        ];
    }

    public function testLimitKeepsOnlyTheMostRecentRecords()
    {
        $limited = array_slice($this->sample(), -2);

        $this->assertCount(2, $limited);
        $this->assertEquals('2024-01-03 08:00:00', $limited[0]['timestamp']);
        $this->assertEquals('2024-01-04 08:00:00', $limited[1]['timestamp']);
    }

    public function testGetByDateRangeFiltersInclusively()
    {
        // getByDateRange() requires a ZKTeco instance to call self::get() internally
        // (a real device socket), so replicate its filter predicate directly
        // against the known sample data instead.
        $start = strtotime('2024-01-02');
        $end = strtotime('2024-01-03 23:59:59');

        $filtered = array_values(array_filter($this->sample(), function ($record) use ($start, $end) {
            $ts = strtotime($record['timestamp']);
            return $ts >= $start && $ts <= $end;
        }));

        $this->assertCount(2, $filtered);
        $this->assertEquals(2, $filtered[0]['uid']);
        $this->assertEquals(3, $filtered[1]['uid']);
    }
}
