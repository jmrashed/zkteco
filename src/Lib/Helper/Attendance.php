<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

class Attendance
{
  /**
   * Fetches attendance data from the ZKTeco device.
   *
   * This method retrieves attendance records from the connected ZKTeco device.
   *
   * @param ZKTeco $self An instance of the ZKTeco class.
   * @param int|null $limit If set, only the $limit most recent records are returned.
   *                        The device protocol always transfers the full log first;
   *                        this only reduces what's parsed/returned client-side.
   * @return array An array of attendance records. Each record contains the following keys:
   *               - uid: User ID
   *               - id: Badge ID (binary)
   *               - state: Attendance state (e.g., 1 - Check In, 2 - Check Out)
   *               - timestamp: Timestamp of the attendance record
   *               - type: Attendance type (might be device specific)
   */
  static public function get(ZKTeco $self, $limit = null)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_ATT_LOG_RRQ; // Attendance log read request command
    $command_string = ''; // Empty command string (no additional data needed)

    $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
    if ($session === false) {
      return []; // Return empty array if session fails
    }

    $attData = Util::recData($self); // Read attendance data from the device

    $attendance = [];
    if (!empty($attData)) {
      $attData = substr($attData, 10); // Skip the first 10 bytes of data

      while (strlen($attData) > 40) { // Loop through each attendance record in the data
        $u = unpack('H78', substr($attData, 0, 39)); // Unpack 78 bytes of data as hexadecimal string

        $u1 = hexdec(substr($u[1], 4, 2)); // Extract first byte of user ID (low order)
        $u2 = hexdec(substr($u[1], 6, 2)); // Extract second byte of user ID (high order)
        $uid = $u1 + ($u2 * 256); // Combine user ID bytes

        $id = hex2bin(substr($u[1], 8, 18)); // Extract badge ID as binary string
        $id = str_replace(chr(0), '', $id); // Remove null bytes from badge ID

        $state = hexdec(substr($u[1], 56, 2)); // Extract attendance state

        $timestamp = Util::decodeTime(hexdec(Util::reverseHex(substr($u[1], 58, 8)))); // Decode timestamp from hex

        $type = hexdec(Util::reverseHex(substr($u[1], 66, 2))); // Extract attendance type

        $attendance[] = [ // Add record to the attendance array
          'uid' => $uid,
          'id' => $id,
          'state' => $state,
          'timestamp' => $timestamp,
          'type' => $type
        ];

        $attData = substr($attData, 40); // Move to the next attendance record data
      }
    }

    if ($limit !== null && $limit >= 0) {
      $attendance = array_slice($attendance, -$limit);
    }

    return $attendance; // Return the parsed attendance data
  }

  /**
   * Fetch attendance data within a date/time range (inclusive).
   *
   * The device protocol has no server-side time-range query, so this still
   * downloads the full attendance log and filters client-side; it's an
   * ergonomics helper, not a performance optimization.
   *
   * @param ZKTeco $self
   * @param string $start Start of range, "Y-m-d H:i:s" or "Y-m-d".
   * @param string $end End of range, "Y-m-d H:i:s" or "Y-m-d".
   * @return array
   */
  static public function getByDateRange(ZKTeco $self, $start, $end)
  {
    $startTs = strtotime($start);
    $endTs = strtotime($end);

    $attendance = self::get($self);

    return array_values(array_filter($attendance, function ($record) use ($startTs, $endTs) {
      $ts = strtotime($record['timestamp']);
      return $ts >= $startTs && $ts <= $endTs;
    }));
  }

  /**
   * Fetch attendance data, discarding records that are clearly corrupted.
   *
   * Some devices (reported for SpeedFace-V5L, see issues #7 / #12) return
   * attendance records where only the first entry decodes correctly and
   * later entries contain obviously-invalid data (uid 0, non-printable
   * badge ids, out-of-range state/type, sentinel/garbage timestamps). This
   * is a real record-format mismatch that needs a raw device packet
   * capture to fix properly (the parsing offsets here match the iClock 680
   * format, which is a different/older record layout) - this method is a
   * best-effort mitigation that filters out records failing basic sanity
   * checks, not a fix for the underlying format difference. It's opt-in
   * (not the default from get()) so it can't silently drop legitimate
   * records for devices that already parse correctly.
   *
   * @param ZKTeco $self
   * @return array
   */
  static public function getSanitized(ZKTeco $self)
  {
    $attendance = self::get($self);

    return array_values(array_filter($attendance, [self::class, 'isPlausibleRecord']));
  }

  /**
   * @param array $record
   * @return bool
   */
  private static function isPlausibleRecord(array $record)
  {
    if (empty($record['uid'])) {
      return false;
    }

    if (isset($record['id']) && $record['id'] !== '' && !ctype_print($record['id'])) {
      return false;
    }

    if (isset($record['state']) && ($record['state'] < 0 || $record['state'] > 15)) {
      return false;
    }

    if (isset($record['type']) && ($record['type'] < 0 || $record['type'] > 15)) {
      return false;
    }

    if (isset($record['timestamp'])) {
      $ts = strtotime($record['timestamp']);
      if ($ts === false || $ts < strtotime('2000-01-02') || $ts > strtotime('+2 years')) {
        return false;
      }
    }

    return true;
  }

  /**
   * Fetch attendance data and merge with user details.
   *
   * @param ZKTeco $self
   * @return array
   */
  static public function getWithUser(ZKTeco $self)
  {
    $attendance = self::get($self);
    if (empty($attendance)) {
      return [];
    }

    $users = User::get($self);
    $userByUid = [];
    foreach ($users as $u) {
      $uid = isset($u['uid']) ? (int) $u['uid'] : null;
      if ($uid !== null) {
        $userByUid[$uid] = $u;
      }
    }

    foreach ($attendance as &$row) {
      $uid = isset($row['uid']) ? (int) $row['uid'] : null;
      if ($uid !== null && isset($userByUid[$uid])) {
        $row['user'] = [
          'uid' => $userByUid[$uid]['uid'] ?? null,
          'userid' => $userByUid[$uid]['userid'] ?? null,
          'name' => $userByUid[$uid]['name'] ?? null,
          'role' => $userByUid[$uid]['role'] ?? null,
          'cardno' => isset($userByUid[$uid]['cardno']) ? trim((string) $userByUid[$uid]['cardno']) : null,
        ];
      } else {
        $row['user'] = null;
      }

      $row['state_name'] = Util::getAttState($row['state'] ?? -1);
      $row['type_name'] = isset($row['type']) ? (string) $row['type'] : 'Unknown';
    }
    unset($row);

    return $attendance;
  }

  /**
   * Clears attendance data from the ZKTeco device.
   *
   * This method sends a command to the ZKTeco device to clear all stored attendance records.
   *
   * @param ZKTeco $self An instance of the ZKTeco class.
   * @return bool|mixed True on success, error message on failure.
   */
  static public function clear(ZKTeco $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_CLEAR_ATT_LOG; // Clear attendance log command
    $command_string = ''; // Empty command string (no additional data needed)

    return $self->_command($command, $command_string); // Send the clear command
  }
}