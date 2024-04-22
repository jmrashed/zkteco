<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\Helper\Util;
use Jmrashed\Zkteco\Lib\ZKTeco;

class Version
{
  /**
   * Retrieves the ZKTeco device version information.
   *
   * This method sends a version command to the ZKTeco device and retrieves the response containing
   * the device's firmware version.
   *
   * @param ZKTeco $self An instance of the ZKTeco class.
   * @return bool|mixed The device version string on success, false on failure.
   */
  static public function get(ZKTeco $self)
  {
    $self->_section = __METHOD__; // Set the current section for internal tracking (optional)

    $command = Util::CMD_VERSION; // Version information command code
    $command_string = ''; // Empty command string (no additional data needed)

    return $self->_command($command, $command_string); // Use internal ZKTeco method to send the command
  }
}