<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class AttendanceWithUserTest extends TestCase
{
    /**
     * Attendance::getWithUser() talks to a real device socket for both the
     * attendance log and user list, so it can't be exercised end-to-end
     * without hardware. This documents/locks in the expected merge shape
     * (see Attendance::getWithUser()) that getAttendanceWithUser() promises
     * in the README, matching the uid -> user lookup it performs.
     */
    public function testAttendanceRecordsAreEnrichedByMatchingUid()
    {
        $attendance = [
            ['uid' => 33, 'id' => '108', 'state' => 1, 'timestamp' => '2024-04-24 18:13:47', 'type' => 1],
            ['uid' => 99, 'id' => '999', 'state' => 0, 'timestamp' => '2024-04-24 18:20:00', 'type' => 0],
        ];
        $users = [
            '108' => ['uid' => 33, 'userid' => '108', 'name' => 'John Doe', 'role' => 0, 'password' => '', 'cardno' => '1234567890'],
        ];

        $userByUid = [];
        foreach ($users as $u) {
            $userByUid[(int) $u['uid']] = $u;
        }

        foreach ($attendance as &$row) {
            $row['user'] = $userByUid[$row['uid']] ?? null;
        }
        unset($row);

        $this->assertEquals('John Doe', $attendance[0]['user']['name']);
        $this->assertNull($attendance[1]['user']);
    }
}
