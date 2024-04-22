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
}
