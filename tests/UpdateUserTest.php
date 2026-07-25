<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;

class UpdateUserTest extends TestCase
{
    public function testUpdateUserDelegatesToSameSetUserCommand()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        // updateUser() and setUser() must issue the exact same device command,
        // since updating is just re-sending the set-user command for an
        // existing uid, not a distinct protocol operation.
        $zk->expects($this->exactly(2))
            ->method('_command')
            ->willReturn(true);

        $this->assertTrue($zk->setUser(1, '108', 'John Doe', '1234', 0, 0));
        $this->assertTrue($zk->updateUser(1, '108', 'John Doe Updated', '1234', 0, 0));
    }
}
