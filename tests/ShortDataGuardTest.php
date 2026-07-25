<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\Helper\Util;
use Jmrashed\Zkteco\Lib\ZKTeco;

class ShortDataGuardTest extends TestCase
{
    public function testCheckValidReturnsFalseInsteadOfWarningOnEmptyReply()
    {
        $this->assertFalse(Util::checkValid(''));
        $this->assertFalse(Util::checkValid('abc'));
    }

    public function testGetSizeReturnsFalseInsteadOfWarningOnShortData()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->getMock();
        $zk->_data_recv = '';

        $this->assertFalse(Util::getSize($zk));

        $zk->_data_recv = 'ab';
        $this->assertFalse(Util::getSize($zk));
    }
}
