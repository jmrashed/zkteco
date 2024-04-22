<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

class WorkCode
{
  /**
   * Retrieves work codes configured on the ZKTeco device.
   *
   * This method sends a command to the ZKTeco device requesting the list of configured work codes.
   * The response may contain information about each work code, depending on the device model.
   *
   * @param ZKTeco $self An instance of the ZKTeco class.
   * @return bool|mixed The work code data retrieved from the device on success, false on failure.
   *                   The exact format of the data depends on the device model.
   */
  static public function get(ZKTeco $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_DEVICE; // Device information command code
    $command_string = 'WorkCode'; // Specific data request: Work Code information

    return $self->_command($command, $command_string); // Use internal ZKTeco method to send the command
  }
}