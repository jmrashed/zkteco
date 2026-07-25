<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\Util;

class AdvancedDeviceManagementTest extends TestCase
{
    private $zk;

    protected function setUp(): void
    {
        // createMock() replaces every public method with a stub returning
        // null by default, including the very methods under test here - so
        // it must only replace _command() (the low-level socket call),
        // letting the real business logic in ZKTeco/Device run. The real
        // constructor is kept (not disabled) so $_zkclient is a real local
        // UDP socket, since a couple of methods below poll it directly via
        // Util/Device internals rather than going through _command().
        $this->zk = $this->getMockBuilder(ZKTeco::class)
            ->setConstructorArgs(['127.0.0.1'])
            ->onlyMethods(['_command'])
            ->getMock();
    }

    public function testDisplayCustomMessage()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->displayCustomMessage('Welcome User', 1, 10);
        
        $this->assertTrue($result);
    }

    public function testDisplayCustomMessageInvalidLine()
    {
        $result = $this->zk->displayCustomMessage('Test', 5, 0);
        
        $this->assertFalse($result);
    }

    public function testDisplayCustomMessageTooLong()
    {
        $longMessage = str_repeat('A', 50);
        
        $result = $this->zk->displayCustomMessage($longMessage, 1, 0);
        
        $this->assertFalse($result);
    }

    public function testOpenDoor()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->openDoor(1);
        
        $this->assertTrue($result);
    }

    public function testCloseDoor()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->closeDoor(1);
        
        $this->assertTrue($result);
    }

    public function testLockDoor()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->lockDoor(1);
        
        $this->assertTrue($result);
    }

    public function testUnlockDoor()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->unlockDoor(1);
        
        $this->assertTrue($result);
    }

    public function testGetDoorStatus()
    {
        $mockStatusData = chr(1) . chr(0) . chr(1) . chr(0); // Door open, unlocked, sensor active
        $this->zk->method('_command')->willReturn($mockStatusData);
        
        $result = $this->zk->getDoorStatus(1);
        
        $this->assertIsArray($result);
        $this->assertTrue($result['door_open']);
        $this->assertFalse($result['door_locked']);
        $this->assertTrue($result['sensor_active']);
        $this->assertArrayHasKey('timestamp', $result);
    }

    public function testGetDoorStatusError()
    {
        $this->zk->method('_command')->willReturn(false);
        
        $result = $this->zk->getDoorStatus(1);
        
        $this->assertArrayHasKey('error', $result);
    }

    public function testSyncTimeZoneDefault()
    {
        // syncTimeZone() goes through Time::set() -> _command() directly,
        // not through the ZKTeco::setTime() wrapper.
        $this->zk->method('_command')->willReturn(true);

        $result = $this->zk->syncTimeZone();

        $this->assertTrue($result);
    }

    public function testSyncTimeZoneCustom()
    {
        $this->zk->method('_command')->willReturn(true);

        $result = $this->zk->syncTimeZone('America/New_York');

        $this->assertTrue($result);
    }

    public function testSyncTimeZoneInvalidTimezone()
    {
        $result = $this->zk->syncTimeZone('Invalid/Timezone');
        
        $this->assertFalse($result);
    }

    public function testGetRealTimeEvents()
    {
        $this->zk->method('_command')->willReturn(true);

        // Short timeout: this polls a real (but silent) local UDP socket for
        // up to $timeout seconds, so keep it small to keep the test fast.
        $result = $this->zk->getRealTimeEvents(1);

        $this->assertIsArray($result);
    }

    public function testGetRealTimeEventsFailure()
    {
        $this->zk->method('_command')->willReturn(false);

        $result = $this->zk->getRealTimeEvents(1);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testStartEventMonitoring()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $callbackCalled = false;
        $callback = function($event) use (&$callbackCalled) {
            $callbackCalled = true;
        };
        
        $result = $this->zk->startEventMonitoring($callback, 1);
        
        $this->assertTrue($result);
    }

    public function testStartEventMonitoringFailure()
    {
        $this->zk->method('_command')->willReturn(false);
        
        $callback = function($event) {};
        
        $result = $this->zk->startEventMonitoring($callback, 1);
        
        $this->assertFalse($result);
    }

    public function testStopEventMonitoring()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->stopEventMonitoring();
        
        $this->assertTrue($result);
    }

    public function testDoorControlConstants()
    {
        $this->assertEquals(1, Util::DOOR_ACTION_OPEN);
        $this->assertEquals(2, Util::DOOR_ACTION_CLOSE);
        $this->assertEquals(3, Util::DOOR_ACTION_LOCK);
        $this->assertEquals(4, Util::DOOR_ACTION_UNLOCK);
    }

    public function testEventTypeConstants()
    {
        $this->assertEquals(1, Util::EVENT_TYPE_ATTENDANCE);
        $this->assertEquals(2, Util::EVENT_TYPE_DOOR_OPEN);
        $this->assertEquals(3, Util::EVENT_TYPE_DOOR_CLOSE);
        $this->assertEquals(4, Util::EVENT_TYPE_ALARM);
        $this->assertEquals(5, Util::EVENT_TYPE_USER_ENROLL);
        $this->assertEquals(6, Util::EVENT_TYPE_USER_DELETE);
        $this->assertEquals(7, Util::EVENT_TYPE_SYSTEM_START);
        $this->assertEquals(8, Util::EVENT_TYPE_SYSTEM_SHUTDOWN);
    }

    public function testEventParsing()
    {
        // 16 bytes, matching Device::_parseEvent()'s expected layout exactly:
        // byte 8 = type, bytes 9-10 = uid, bytes 11-14 = timestamp, byte 15 = state
        $eventData = str_repeat(chr(0), 8) . // Header
                    chr(1) . // Event type (attendance)
                    chr(123) . chr(0) . // UID (123)
                    chr(100) . chr(200) . chr(50) . chr(25) . // Timestamp
                    chr(1); // State

        // This would be tested in integration tests with actual device
        $this->assertIsString($eventData);
        $this->assertEquals(16, strlen($eventData));
    }

    public function testDoorStatusParsing()
    {
        // Test door status parsing with various combinations
        $testCases = [
            [chr(1) . chr(1) . chr(1) . chr(0), true, true, true, false], // Open, locked, sensor active
            [chr(0) . chr(0) . chr(0) . chr(0), false, false, false, false], // Closed, unlocked, sensor inactive
            [chr(3) . chr(1) . chr(1) . chr(0), true, true, true, true], // Open with alarm
        ];
        
        foreach ($testCases as [$data, $expectedOpen, $expectedLocked, $expectedSensor, $expectedAlarm]) {
            // A fresh mock per case: configuring ->method() again on the
            // same mock doesn't replace the first stub, it stacks another
            // "any invocation" matcher that never gets reached.
            $zk = $this->getMockBuilder(ZKTeco::class)
                ->setConstructorArgs(['127.0.0.1'])
                ->onlyMethods(['_command'])
                ->getMock();
            $zk->method('_command')->willReturn($data);

            $result = $zk->getDoorStatus(1);
            
            $this->assertEquals($expectedOpen, $result['door_open']);
            $this->assertEquals($expectedLocked, $result['door_locked']);
            $this->assertEquals($expectedSensor, $result['sensor_active']);
            $this->assertEquals($expectedAlarm, $result['alarm_active']);
        }
    }
}