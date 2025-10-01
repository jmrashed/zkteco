<?php

namespace Jmrashed\Zkteco\Lib\Helper;

use Jmrashed\Zkteco\Lib\ZKTeco;

class Face
{
    const FACE_TEMPLATE_SIZE = 1024;
    const MAX_FACE_TEMPLATES = 5;

    /**
     * Turn on the face recognition feature of the device.
     *
     * @param ZKTeco $self The instance of the ZKTeco class.
     * @return bool|mixed Returns true if the face recognition feature is turned on successfully, false otherwise.
     */
    static public function on(ZKTeco $self)
    {
        $self->_section = __METHOD__;

        $command = Util::CMD_DEVICE;
        $command_string = 'FaceFunOn';

        return $self->_command($command, $command_string);
    }

    /**
     * Retrieve face template data for a specific user.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @return array Face template data.
     */
    static public function getData(ZKTeco $self, $uid)
    {
        $self->_section = __METHOD__;
        
        $data = [];
        
        // Face templates are typically stored with IDs 50-54
        for ($i = 0; $i < self::MAX_FACE_TEMPLATES; $i++) {
            $faceId = 50 + $i;
            $template = self::_getFaceTemplate($self, $uid, $faceId);
            
            if ($template['size'] > 0) {
                $data[$faceId] = [
                    'template' => $template['data'],
                    'size' => $template['size'],
                    'quality' => self::_calculateQuality($template['data'])
                ];
            }
        }
        
        return $data;
    }

    /**
     * Set face template data for a specific user.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @param array $faceData Face template data.
     * @return bool Success status.
     */
    static public function setData(ZKTeco $self, $uid, array $faceData)
    {
        $self->_section = __METHOD__;
        
        $success = true;
        
        foreach ($faceData as $faceId => $data) {
            if (!self::_setFaceTemplate($self, $uid, $faceId, $data['template'])) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Enroll a face recognition template for a user.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @param string $templateData Face template data.
     * @return bool Success status.
     */
    static public function enrollTemplate(ZKTeco $self, $uid, $templateData)
    {
        $self->_section = __METHOD__;
        
        // Find next available face template slot
        $faceId = self::_findAvailableSlot($self, $uid);
        
        if ($faceId === false) {
            return false; // No available slots
        }
        
        return self::_setFaceTemplate($self, $uid, $faceId, $templateData);
    }

    /**
     * Get face template from device.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @param int $faceId Face template ID.
     * @return array Template data and size.
     */
    private static function _getFaceTemplate(ZKTeco $self, $uid, $faceId)
    {
        $command = Util::CMD_USER_TEMP_RRQ;
        $byte1 = chr($uid % 256);
        $byte2 = chr($uid >> 8);
        $command_string = $byte1 . $byte2 . chr($faceId);
        
        $ret = ['size' => 0, 'data' => ''];
        
        $session = $self->_command($command, $command_string, Util::COMMAND_TYPE_DATA);
        if ($session === false) {
            return $ret;
        }
        
        $data = Util::recData($self, 10, false);
        
        if (!empty($data)) {
            $ret['size'] = strlen($data);
            $ret['data'] = $data;
        }
        
        return $ret;
    }

    /**
     * Set face template on device.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @param int $faceId Face template ID.
     * @param string $templateData Template data.
     * @return bool Success status.
     */
    private static function _setFaceTemplate(ZKTeco $self, $uid, $faceId, $templateData)
    {
        $command = Util::CMD_USER_TEMP_WRQ;
        
        $templateSize = strlen($templateData);
        $byte1 = chr($uid % 256);
        $byte2 = chr($uid >> 8);
        
        $prefix = chr($templateSize % 256) . chr($templateSize >> 8) . 
                 $byte1 . $byte2 . chr($faceId) . chr(1);
        
        $command_string = $prefix . $templateData;
        
        return $self->_command($command, $command_string);
    }

    /**
     * Find available face template slot for user.
     *
     * @param ZKTeco $self ZKTeco instance.
     * @param int $uid User ID.
     * @return int|false Available slot ID or false if none available.
     */
    private static function _findAvailableSlot(ZKTeco $self, $uid)
    {
        for ($i = 0; $i < self::MAX_FACE_TEMPLATES; $i++) {
            $faceId = 50 + $i;
            $template = self::_getFaceTemplate($self, $uid, $faceId);
            
            if ($template['size'] == 0) {
                return $faceId;
            }
        }
        
        return false;
    }

    /**
     * Calculate face template quality score.
     *
     * @param string $templateData Template data.
     * @return int Quality score (0-100).
     */
    private static function _calculateQuality($templateData)
    {
        if (empty($templateData)) {
            return 0;
        }
        
        $length = strlen($templateData);
        
        // Basic quality assessment based on template size and data distribution
        $sizeScore = min(100, ($length / self::FACE_TEMPLATE_SIZE) * 50);
        
        $complexity = 0;
        for ($i = 0; $i < min($length, 200); $i += 10) {
            $complexity += ord($templateData[$i]);
        }
        
        $complexityScore = min(50, ($complexity / min($length / 10, 20)) * 0.2);
        
        return (int)($sizeScore + $complexityScore);
    }
}
