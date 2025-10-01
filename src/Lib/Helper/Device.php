<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

class Device
{
    /**
     * Get the name of the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns the device name if successful, false otherwise.
     */
    static public function name(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = '~DeviceName';

        return $self->_command($command, $command_string);
    }

    /**
     * Enable the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is enabled successfully, false otherwise.
     */
    static public function enable(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_ENABLE_DEVICE;
        $command_string = '';

        return $self->_command($command, $command_string);
    }

    /**
     * Disable the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is disabled successfully, false otherwise.
     */
    static public function disable(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DISABLE_DEVICE;
        $command_string = chr(0) . chr(0);

        return $self->_command($command, $command_string);
    }

    /**
     * Power off the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is powered off successfully, false otherwise.
     */
    public static function powerOff(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_POWEROFF;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }

    /**
     * Restart the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is restarted successfully, false otherwise.
     */
    public static function restart(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_RESTART;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }

    /**
     * Sleep the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is put to sleep successfully, false otherwise.
     */
    public static function sleep(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_SLEEP;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }

    /**
     * Resume the device from sleep.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device is resumed successfully, false otherwise.
     */
    public static function resume(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_RESUME;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }

    /**
     * Test the device's voice.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the device's voice test is successful, false otherwise.
     */
    public static function testVoice(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_TESTVOICE;
        $command_string = chr(0) . chr(0);
        return $self->_command($command, $command_string);
    }

    /**
     * Clear the device's LCD screen.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the LCD screen is cleared successfully, false otherwise.
     */
    public static function clearLCD(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_CLEAR_LCD;
        return $self->_command($command, '');
    }

    /**
     * Write text into the device's LCD screen.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @param int $rank Line number of text.
     * @param string $text Text which will be displayed on the LCD screen.
     * @return bool|mixed Returns true if the text is written to the LCD successfully, false otherwise.
     */
    public static function writeLCD(ZKTeco $self, $rank, $text)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_WRITE_LCD;
        $byte1 = chr((int)($rank % 256));
        $byte2 = chr((int)($rank >> 8));
        $byte3 = chr(0);
        $command_string = $byte1.$byte2.$byte3.' '.$text;
        return $self->_command($command, $command_string);
    }

    /**
     * Display custom message on LCD screen with formatting options.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param string $message Message to display.
     * @param int $line Line number (1-4).
     * @param int $duration Display duration in seconds (0 = permanent).
     * @return bool Success status.
     */
    public static function displayCustomMessage(ZKTeco $self, $message, $line = 1, $duration = 0)
    {
        $self->_section = __METHOD__;
        
        if ($line < 1 || $line > 4 || strlen($message) > 32) {
            return false;
        }
        
        $success = self::writeLCD($self, $line, $message);
        
        if ($success && $duration > 0) {
            // Schedule message clearing after duration
            self::_scheduleMessageClear($self, $line, $duration);
        }
        
        return $success;
    }

    /**
     * Open door remotely.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $doorId Door ID (default 1).
     * @return bool Success status.
     */
    public static function openDoor(ZKTeco $self, $doorId = 1)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_DOOR_CONTROL;
        $command_string = chr($doorId) . chr(Util::DOOR_ACTION_OPEN) . chr(0) . chr(0);
        
        return $self->_command($command, $command_string);
    }

    /**
     * Close door remotely.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $doorId Door ID (default 1).
     * @return bool Success status.
     */
    public static function closeDoor(ZKTeco $self, $doorId = 1)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_DOOR_CONTROL;
        $command_string = chr($doorId) . chr(Util::DOOR_ACTION_CLOSE) . chr(0) . chr(0);
        
        return $self->_command($command, $command_string);
    }

    /**
     * Lock door remotely.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $doorId Door ID (default 1).
     * @return bool Success status.
     */
    public static function lockDoor(ZKTeco $self, $doorId = 1)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_DOOR_CONTROL;
        $command_string = chr($doorId) . chr(Util::DOOR_ACTION_LOCK) . chr(0) . chr(0);
        
        return $self->_command($command, $command_string);
    }

    /**
     * Unlock door remotely.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $doorId Door ID (default 1).
     * @return bool Success status.
     */
    public static function unlockDoor(ZKTeco $self, $doorId = 1)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_DOOR_CONTROL;
        $command_string = chr($doorId) . chr(Util::DOOR_ACTION_UNLOCK) . chr(0) . chr(0);
        
        return $self->_command($command, $command_string);
    }

    /**
     * Get door status.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $doorId Door ID (default 1).
     * @return array Door status information.
     */
    public static function getDoorStatus(ZKTeco $self, $doorId = 1)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_DOOR_STATUS;
        $command_string = chr($doorId) . chr(0) . chr(0) . chr(0);
        
        $result = $self->_command($command, $command_string);
        
        if ($result === false) {
            return ['error' => 'Failed to get door status'];
        }
        
        return self::_parseDoorStatus($result);
    }

    /**
     * Synchronize device time with server timezone.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param string $timezone Timezone identifier (e.g., 'America/New_York').
     * @return bool Success status.
     */
    public static function syncTimeZone(ZKTeco $self, $timezone = null)
    {
        $self->_section = __METHOD__;
        
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }
        
        try {
            $dateTime = new \DateTime('now', new \DateTimeZone($timezone));
            $timeString = $dateTime->format('Y-m-d H:i:s');
            
            return Time::set($self, $timeString);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get real-time events from device.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $timeout Timeout in seconds.
     * @return array Real-time events.
     */
    public static function getRealTimeEvents(ZKTeco $self, $timeout = 30)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_REG_EVENT;
        $command_string = chr(1) . chr(0) . chr(0) . chr(0); // Enable real-time events
        
        $result = $self->_command($command, $command_string);
        
        if ($result === false) {
            return [];
        }
        
        return self::_pollEvents($self, $timeout);
    }

    /**
     * Start real-time event monitoring.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param callable $callback Callback function to handle events.
     * @param int $timeout Monitoring timeout in seconds.
     * @return bool Success status.
     */
    public static function startEventMonitoring(ZKTeco $self, callable $callback, $timeout = 0)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_REG_EVENT;
        $command_string = chr(1) . chr(0) . chr(0) . chr(0);
        
        $result = $self->_command($command, $command_string);
        
        if ($result === false) {
            return false;
        }
        
        $startTime = time();
        
        while ($timeout === 0 || (time() - $startTime) < $timeout) {
            $events = self::_pollEvents($self, 5);
            
            foreach ($events as $event) {
                call_user_func($callback, $event);
            }
            
            usleep(100000); // 100ms delay
        }
        
        return true;
    }

    /**
     * Stop real-time event monitoring.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @return bool Success status.
     */
    public static function stopEventMonitoring(ZKTeco $self)
    {
        $self->_section = __METHOD__;
        
        $command = Util::CMD_REG_EVENT;
        $command_string = chr(0) . chr(0) . chr(0) . chr(0); // Disable real-time events
        
        return $self->_command($command, $command_string);
    }

    /**
     * Schedule message clearing after duration.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $line Line number.
     * @param int $duration Duration in seconds.
     */
    private static function _scheduleMessageClear(ZKTeco $self, $line, $duration)
    {
        // This would typically be handled by a background process or queue
        // For now, we'll use a simple approach
        register_shutdown_function(function() use ($self, $line, $duration) {
            sleep($duration);
            self::writeLCD($self, $line, '');
        });
    }

    /**
     * Parse door status response.
     *
     * @param string $data Raw door status data.
     * @return array Parsed door status.
     */
    private static function _parseDoorStatus($data)
    {
        if (strlen($data) < 4) {
            return ['error' => 'Invalid door status data'];
        }
        
        $status = ord($data[0]);
        $lockStatus = ord($data[1]);
        $sensorStatus = ord($data[2]);
        
        return [
            'door_open' => ($status & 1) === 1,
            'door_locked' => ($lockStatus & 1) === 1,
            'sensor_active' => ($sensorStatus & 1) === 1,
            'alarm_active' => ($status & 2) === 2,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Poll for real-time events.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $timeout Timeout in seconds.
     * @return array Events array.
     */
    private static function _pollEvents(ZKTeco $self, $timeout)
    {
        $events = [];
        $startTime = time();
        
        while ((time() - $startTime) < $timeout) {
            try {
                $ret = @socket_recvfrom($self->_zkclient, $data, 1024, MSG_DONTWAIT, $self->_ip, $self->_port);
                
                if ($ret !== false && strlen($data) > 8) {
                    $event = self::_parseEvent($data);
                    if ($event) {
                        $events[] = $event;
                    }
                }
            } catch (\Exception $e) {
                // Continue polling
            }
            
            usleep(50000); // 50ms delay
        }
        
        return $events;
    }

    /**
     * Parse real-time event data.
     *
     * @param string $data Raw event data.
     * @return array|null Parsed event or null if invalid.
     */
    private static function _parseEvent($data)
    {
        if (strlen($data) < 16) {
            return null;
        }
        
        $eventType = ord($data[8]);
        $uid = ord($data[9]) + (ord($data[10]) << 8);
        $timestamp = ord($data[11]) + (ord($data[12]) << 8) + (ord($data[13]) << 16) + (ord($data[14]) << 24);
        $state = ord($data[15]);
        
        return [
            'type' => self::_getEventTypeName($eventType),
            'uid' => $uid,
            'timestamp' => Util::decodeTime($timestamp),
            'state' => $state,
            'raw_data' => bin2hex($data)
        ];
    }

    /**
     * Get event type name.
     *
     * @param int $eventType Event type code.
     * @return string Event type name.
     */
    private static function _getEventTypeName($eventType)
    {
        $eventTypes = [
            1 => 'attendance',
            2 => 'door_open',
            3 => 'door_close',
            4 => 'alarm',
            5 => 'user_enroll',
            6 => 'user_delete',
            7 => 'system_start',
            8 => 'system_shutdown'
        ];
        
        return $eventTypes[$eventType] ?? 'unknown';
    }
}
