<?php

namespace Jmrashed\Zkteco\Lib;

use ErrorException;
use Exception;
use Jmrashed\Zkteco\Lib\Helper\Attendance;
use Jmrashed\Zkteco\Lib\Helper\Connect;
use Jmrashed\Zkteco\Lib\Helper\Device;
use Jmrashed\Zkteco\Lib\Helper\Face;
use Jmrashed\Zkteco\Lib\Helper\Fingerprint;
use Jmrashed\Zkteco\Lib\Helper\Os;
use Jmrashed\Zkteco\Lib\Helper\Pin;
use Jmrashed\Zkteco\Lib\Helper\Platform;
use Jmrashed\Zkteco\Lib\Helper\SerialNumber;
use Jmrashed\Zkteco\Lib\Helper\Ssr;
use Jmrashed\Zkteco\Lib\Helper\Time;
use Jmrashed\Zkteco\Lib\Helper\User;
use Jmrashed\Zkteco\Lib\Helper\Util;
use Jmrashed\Zkteco\Lib\Helper\Version;
use Jmrashed\Zkteco\Lib\Helper\WorkCode;

class ZKTeco
{
    public $_ip;
    public $_port;
    public $_zkclient;

    public $_data_recv = '';
    public $_session_id = 0;
    public $_section = '';

/**
 * ZKLib constructor.
 *
 * @param string $ip Device IP address.
 * @param int $port Port number. Default: 4370.
 */
    public function __construct($ip, $port = 4370)
    {
        // Set the IP address and port.
        $this->_ip = $ip;
        $this->_port = $port;

        // Create a UDP socket.
        $this->_zkclient = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        // Set the receive timeout to 60 seconds and 500 milliseconds.
        $timeout = array('sec' => 60, 'usec' => 500000);
        socket_set_option($this->_zkclient, SOL_SOCKET, SO_RCVTIMEO, $timeout);
    }

    /**
     * Create and send command to device
     *
     * @param string $command
     * @param string $command_string
     * @param string $type
     * @return bool|mixed
     */
    public function _command($command, $command_string, $type = Util::COMMAND_TYPE_GENERAL)
    {
        $chksum = 0;
        $session_id = $this->_session_id;

        $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($this->_data_recv, 0, 8));
        $reply_id = hexdec($u['h8'] . $u['h7']);

        $buf = Util::createHeader($command, $chksum, $session_id, $reply_id, $command_string);

        socket_sendto($this->_zkclient, $buf, strlen($buf), 0, $this->_ip, $this->_port);

        try {
            @socket_recvfrom($this->_zkclient, $this->_data_recv, 1024, 0, $this->_ip, $this->_port);

            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6', substr($this->_data_recv, 0, 8));

            $ret = false;
            $session = hexdec($u['h6'] . $u['h5']);

            if ($type === Util::COMMAND_TYPE_GENERAL && $session_id === $session) {
                $ret = substr($this->_data_recv, 8);
            } else if ($type === Util::COMMAND_TYPE_DATA && !empty($session)) {
                $ret = $session;
            }

            return $ret;
        } catch (ErrorException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

/**
 * Connects to the device.
 *
 * @return bool True if successfully connected, otherwise false.
 */
    public function connect()
    {
        // Call the static method connect of the Connect class, passing $this (current instance)
        // to connect to the device.
        return Connect::connect($this);
    }

/**
 * Disconnects from the device.
 *
 * @return bool True if successfully disconnected, otherwise false.
 */
    public function disconnect()
    {
        // Call the static method disconnect of the Connect class, passing $this (current instance)
        // to disconnect from the device.
        return Connect::disconnect($this);
    }

/**
 * Retrieves the version information of the device.
 *
 * @return bool|mixed The version information of the device, or the result from Version::get if retrieval fails.
 */
    public function version()
    {
        // Call the static method get of the Version class, passing $this (current instance)
        // to retrieve the version information of the device.
        return Version::get($this);
    }

/**
 * Retrieves the operating system (OS) version from the device.
 *
 * @return bool|mixed The OS version from the device, or the result from Os::get if retrieval fails.
 */
    public function osVersion()
    {
        // Call the static method get of the Os class, passing $this (current instance)
        // to retrieve the OS version from the device.
        return Os::get($this);
    }

/**
 * Retrieves the platform information from the device.
 *
 * @return bool|mixed The platform information from the device, or the result from Platform::get if retrieval fails.
 */
    public function platform()
    {
        // Call the static method get of the Platform class, passing $this (current instance)
        // to retrieve the platform information from the device.
        return Platform::get($this);
    }

    /**
     * Retrieves the firmware version of the device.
     *
     * @return bool|mixed The firmware version of the device, or the result from Platform::getVersion if retrieval fails.
     */
    public function fmVersion()
    {
        // Call the static method getVersion of the Platform class, passing $this (current instance)
        // to retrieve the firmware version of the device.
        return Platform::getVersion($this);
    }

/**
 * Retrieves the work code from the device.
 *
 * @return bool|mixed The work code from the device, or the result from WorkCode::get if retrieval fails.
 */
    public function workCode()
    {
        // Call the static method get of the WorkCode class, passing $this (current instance)
        // to retrieve the work code from the device.
        return WorkCode::get($this);
    }

/**
 * Retrieves the SSR (Self-Service Recorder) information from the device.
 *
 * @return bool|mixed The SSR information from the device, or the result from Ssr::get if retrieval fails.
 */
    public function ssr()
    {
        // Call the static method get of the Ssr class, passing $this (current instance)
        // to retrieve the SSR information from the device.
        return Ssr::get($this);
    }

/**
 * Retrieves the pin width of the device.
 *
 * @return bool|mixed The pin width of the device, or the result from Pin::width if retrieval fails.
 */
    public function pinWidth()
    {
        // Call the static method width of the Pin class, passing $this (current instance)
        // to retrieve the pin width of the device.
        return Pin::width($this);
    }

/**
 * Enables the face recognition function on the device.
 *
 * @return bool|mixed True if the face recognition function was successfully enabled, otherwise returns the result from Face::on.
 */
    public function faceFunctionOn()
    {
        // Call the static method on of the Face class, passing $this (current instance)
        // to enable the face recognition function on the device.
        return Face::on($this);
    }

/**
 * Retrieves the serial number of the device.
 *
 * @return bool|mixed The serial number of the device, or the result from SerialNumber::get if retrieval fails.
 */
    public function serialNumber()
    {
        // Call the static method get of the SerialNumber class, passing $this (current instance)
        // to retrieve the serial number of the device.
        return SerialNumber::get($this);
    }

/**
 * Retrieves the name of the device.
 *
 * @return bool|mixed The name of the device, or the result from Device::name if retrieval fails.
 */
    public function deviceName()
    {
        // Call the static method name of the Device class, passing $this (current instance)
        // to retrieve the name of the device.
        return Device::name($this);
    }

/**
 * Disables the device.
 *
 * @return bool|mixed True if the device was successfully disabled, otherwise returns the result from Device::disable.
 */
    public function disableDevice()
    {
        // Call the static method disable of the Device class, passing $this (current instance)
        // to disable the device.
        return Device::disable($this);
    }

/**
 * Enables the device.
 *
 * @return bool|mixed True if the device was successfully enabled, otherwise returns the result from Device::enable.
 */
    public function enableDevice()
    {
        // Call the static method enable of the Device class, passing $this (current instance)
        // to enable the device.
        return Device::enable($this);
    }

/**
 * Retrieves user data from the device.
 *
 * @return array An array containing user data for each user, structured as [userid, name, cardno, uid, role, password].
 */
    public function getUser()
    {
        // Call the static method get of the User class, passing $this (current instance)
        // to retrieve user data from the device.
        return User::get($this);
    }

/**
 * Sets user data for the specified user.
 *
 * @param int $uid Unique ID (max 65535) of the user.
 * @param int|string $userid ID in DB (same as $uid, max length = 9, only numbers - depends on device setting).
 * @param string $name Name of the user (max length = 24).
 * @param int|string $password Password for the user (max length = 8, only numbers - depends on device setting).
 * @param int $role Default Util::LEVEL_USER. Role of the user (e.g., admin or regular user).
 * @param int $cardno Default 0 (max length = 10, only numbers). Card number associated with the user.
 * @return bool|mixed True if user data was successfully set, otherwise returns the result from User::set.
 */
    public function setUser($uid, $userid, $name, $password, $role = Util::LEVEL_USER, $cardno = 0)
    {
        // Call the static method set of the User class, passing $this (current instance),
        // along with the user data: $uid, $userid, $name, $password, $role, and $cardno.
        return User::set($this, $uid, $userid, $name, $password, $role, $cardno);
    }

/**
 * Removes all users from the device.
 *
 * @return bool|mixed True if all users were successfully removed, otherwise returns the result from User::clear.
 */
    public function clearUsers()
    {
        // Call the static method clear of the User class, passing $this (current instance)
        // to remove all users from the device.
        return User::clear($this);
    }

/**
 * Removes the admin privileges from the current user.
 *
 * @return bool|mixed True if the admin privileges were successfully removed, otherwise returns the result from User::clearAdmin.
 */
    public function clearAdmin()
    {
        // Call the static method clearAdmin of the User class, passing $this (current instance)
        // to remove the admin privileges from the current user.
        return User::clearAdmin($this);
    }

/**
 * Removes a user identified by the specified UID from the device.
 *
 * @param integer $uid The unique ID of the user to be removed.
 * @return bool|mixed True if the user was successfully removed, otherwise returns the result from User::remove.
 */
    public function removeUser($uid)
    {
        // Call the static method remove of the User class, passing $this (current instance) and $uid (unique ID)
        // to remove the user from the device.
        return User::remove($this, $uid);
    }

/**
 * Retrieves the fingerprint data array for the specified UID.
 *
 * @param integer $uid Unique ID (max 65535) of the user whose fingerprint data will be retrieved.
 * @return array Binary fingerprint data array, where the key is finger ID (0-9).
 * TODO: Can get data, but don't know how to parse the data. More documentation is needed to understand how to parse the data.
 */
    public function getFingerprint($uid)
    {
        // Call the static method get of the Fingerprint class, passing $this (current instance) and $uid (unique ID)
        // to retrieve the fingerprint data array.
        return Fingerprint::get($this, $uid);
    }

/**
 * Sets the fingerprint data array for the specified UID.
 *
 * @param integer $uid Unique ID (max 65535) of the user for whom the fingerprints will be set.
 * @param array $data Binary fingerprint data array, where the key is finger ID (0-9) same as returned array from 'getFingerprint' method.
 * @return int The count of added fingerprints.
 * TODO: Still cannot set fingerprint. More documentation is needed to understand the process.
 */
    public function setFingerprint($uid, array $data)
    {
        // Call the static method set of the Fingerprint class, passing $this (current instance),
        // $uid (unique ID), and $data (binary fingerprint data array) to set the fingerprints.
        return Fingerprint::set($this, $uid, $data);
    }

/**
 * Removes fingerprints associated with the specified UID and fingers ID array from the device.
 *
 * @param integer $uid Unique ID (max 65535) of the user whose fingerprints will be removed.
 * @param array $data Array containing the fingers ID (0-9) of the fingerprints to be removed.
 * @return int The count of deleted fingerprints.
 */
    public function removeFingerprint($uid, array $data)
    {
        // Call the static method remove of the Fingerprint class, passing $this (current instance),
        // $uid (unique ID), and $data (fingers ID array) to remove the specified fingerprints.
        return Fingerprint::remove($this, $uid, $data);
    }

/**
 * Retrieves the attendance log from the device.
 *
 * @return array An array containing attendance log entries, each entry structured as [uid, id, state, timestamp].
 */
    public function getAttendance()
    {
        // Call the static method get of the Attendance class, passing $this (current instance)
        // to retrieve the attendance log from the device.
        return Attendance::get($this);
    }

// Modify the getAttendance() method in the ZKTeco class
    public function getAttendanceWithLimit($limit = 10)
    {
        // Call the static method get of the Attendance class, passing $this (current instance)
        // and $limit (number of latest records to retrieve) to retrieve the attendance log from the device.
        return Attendance::get($this, $limit);
    }

    public static function getTodaysRecords(ZKTeco $self)
    {
        // Get all attendance records from the device
        $attendanceData = self::get($self);

        // Get today's date
        $currentDate = date('Y-m-d');

        // Filter attendance data for today
        $todaysAttendance = array_filter($attendanceData, function ($record) use ($currentDate) {
            // Assuming the date format in the attendance data is 'Y-m-d H:i:s'
            return substr($record['timestamp'], 0, 10) === $currentDate;
        });

        return $todaysAttendance;
    }

/**
 * Clears the attendance log of the device.
 *
 * @return bool|mixed True if the attendance log was successfully cleared, otherwise returns the result from Attendance::clear.
 */
    public function clearAttendance()
    {
        // Call the static method clear of the Attendance class, passing $this (current instance)
        // to clear the attendance log of the device.
        return Attendance::clear($this);
    }

/**
 * Sets the device time to the specified value.
 *
 * @param string $t The time to set, in the format "Y-m-d H:i:s".
 * @return bool|mixed True if the device time was successfully set, otherwise returns the result from Time::set.
 */
    public function setTime($t)
    {
        // Call the static method set of the Time class, passing $this (current instance)
        // and the specified time $t to set the device time.
        return Time::set($this, $t);
    }

/**
 * Retrieves the current time from the device.
 *
 * @return bool|mixed The current time in the format "Y-m-d H:i:s", or the result from Time::get.
 */
    public function getTime()
    {
        // Call the static method get of the Time class, passing $this (current instance)
        // to retrieve the current time from the device.
        return Time::get($this);
    }

/**
 * Shuts down the device.
 *
 * @return bool|mixed True if the device was successfully shut down, otherwise returns the result from Device::powerOff.
 */
    public function shutdown()
    {
        // Call the static method powerOff of the Device class, passing $this (current instance)
        // to power off the device.
        return Device::powerOff($this);
    }

/**
 * Restarts the device.
 *
 * @return bool|mixed True if the device restarted successfully, otherwise returns the result from Device::restart.
 */
    public function restart()
    {
        // Call the static method restart of the Device class, passing $this (current instance)
        // to restart the device.
        return Device::restart($this);
    }

/**
 * Puts the device into sleep mode.
 *
 * @return bool|mixed True if the device entered sleep mode successfully, otherwise returns the result from Device::sleep.
 */
    public function sleep()
    {
        // Call the static method sleep of the Device class, passing $this (current instance)
        // to put the device into sleep mode.
        return Device::sleep($this);
    }

/**
 * Resumes the device from sleep mode.
 *
 * @return bool|mixed True if the device was successfully resumed, otherwise returns the result from Device::resume.
 */
    public function resume()
    {
        // Call the static method resume of the Device class, passing $this (current instance)
        // to resume the device from sleep mode.
        return Device::resume($this);
    }

/**
 * Performs a voice test by producing the sound "Thank you".
 *
 * @return bool|mixed True if the voice test was successful, otherwise returns the result from Device::testVoice.
 */
    public function testVoice()
    {
        // Call the static method testVoice of the Device class, passing $this (current instance)
        // to perform the voice test.
        return Device::testVoice($this);
    }

/**
 * Clears the content displayed on the LCD screen.
 *
 * @return bool True if the content was successfully cleared, false otherwise.
 */
    public function clearLCD()
    {
        // Call the static method clearLCD of the Device class, passing $this (current instance)
        // to identify the LCD screen that needs to be cleared.
        return Device::clearLCD($this);
    }

/**
 * Writes a welcome message to the LCD screen.
 *
 * @return bool True if the message was successfully written, false otherwise.
 */
    public function writeLCD()
    {
        // Call the static method writeLCD of the Device class, passing $this (current instance),
        // 2 (the LCD screen identifier), and "Welcome Jmrashed" (the message to display).
        return Device::writeLCD($this, 2, "Welcome Jmrashed");
    }

}
