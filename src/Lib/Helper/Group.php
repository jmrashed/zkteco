<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

/**
 * User group management (assigning users to a group, and assigning
 * time zones to a group), using CMD_USERGRP_RRQ/WRQ and CMD_GRPTZ_RRQ/WRQ.
 *
 * NOTE: unlike User/Attendance, this record layout has not been verified
 * against a physical device capture. It follows the same uid/id encoding
 * conventions used elsewhere in this library (low byte, high byte) and the
 * command codes requested in https://github.com/jmrashed/zkteco/issues/15.
 * Please report back if this doesn't match your device's behavior.
 */
class Group
{
    /**
     * Assign a user to a group.
     *
     * @param ZKTeco $self
     * @param int $uid Unique ID (max 65535) of the user.
     * @param int $groupId Group ID (max 255).
     * @return bool|mixed
     */
    static public function setUserGroup(ZKTeco $self, $uid, $groupId)
    {
        $self->_section = __METHOD__;

        if ((int) $uid === 0 || (int) $uid > Util::USHRT_MAX || (int) $groupId < 0 || (int) $groupId > 255) {
            return false;
        }

        $command = Util::CMD_USERGRP_WRQ;
        $byte1 = chr((int) ($uid % 256));
        $byte2 = chr((int) ($uid >> 8));
        $command_string = $byte1 . $byte2 . chr((int) $groupId);

        return $self->_command($command, $command_string);
    }

    /**
     * Get the group ID a user is assigned to.
     *
     * @param ZKTeco $self
     * @param int $uid Unique ID (max 65535) of the user.
     * @return int|false Group ID, or false on failure.
     */
    static public function getUserGroup(ZKTeco $self, $uid)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_USERGRP_RRQ;
        $byte1 = chr((int) ($uid % 256));
        $byte2 = chr((int) ($uid >> 8));
        $command_string = $byte1 . $byte2;

        $reply = $self->_command($command, $command_string, Util::COMMAND_TYPE_GENERAL);
        if ($reply === false || strlen($reply) < 1) {
            return false;
        }

        return ord($reply[0]);
    }

    /**
     * Assign up to 3 time zones to a group.
     *
     * @param ZKTeco $self
     * @param int $groupId Group ID (max 255).
     * @param array $timezoneIds Up to 3 time zone IDs.
     * @return bool|mixed
     */
    static public function setGroupTimezones(ZKTeco $self, $groupId, array $timezoneIds)
    {
        $self->_section = __METHOD__;

        if ((int) $groupId < 0 || (int) $groupId > 255 || count($timezoneIds) > 3) {
            return false;
        }

        $timezoneIds = array_pad(array_slice($timezoneIds, 0, 3), 3, 0);

        $command = Util::CMD_GRPTZ_WRQ;
        $command_string = chr((int) $groupId);
        foreach ($timezoneIds as $timezoneId) {
            $command_string .= chr((int) $timezoneId % 256) . chr((int) $timezoneId >> 8);
        }

        return $self->_command($command, $command_string);
    }

    /**
     * Get the time zones assigned to a group.
     *
     * @param ZKTeco $self
     * @param int $groupId Group ID (max 255).
     * @return array Up to 3 time zone IDs, or an empty array on failure.
     */
    static public function getGroupTimezones(ZKTeco $self, $groupId)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_GRPTZ_RRQ;
        $command_string = chr((int) $groupId);

        $reply = $self->_command($command, $command_string, Util::COMMAND_TYPE_GENERAL);
        if ($reply === false || strlen($reply) < 6) {
            return [];
        }

        $timezones = [];
        for ($i = 0; $i < 3; $i++) {
            $offset = $i * 2;
            $timezones[] = ord($reply[$offset]) + (ord($reply[$offset + 1]) * 256);
        }

        return $timezones;
    }
}
