<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\Util;

class SendWithBufferTest extends TestCase
{
    public function testSmallPayloadIsSentDirectly()
    {
        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->expects($this->once())
            ->method('_command')
            ->with(999, 'small-payload')
            ->willReturn(true);

        $result = Util::sendWithBuffer($zk, 999, 'small-payload');

        $this->assertTrue($result);
    }

    public function testLargePayloadIsChunkedThroughPrepareDataAndData()
    {
        $data = str_repeat('A', Util::MAX_CHUNK_SIZE) . str_repeat('B', 200);
        $calls = [];

        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturnCallback(function ($command, $string) use (&$calls) {
            $calls[] = [$command, $string];
            return true;
        });

        $result = Util::sendWithBuffer($zk, 999, $data);

        $this->assertTrue($result);
        $this->assertCount(4, $calls);

        // 1. CMD_PREPARE_DATA with the total size packed as a 4-byte uint
        $this->assertEquals(Util::CMD_PREPARE_DATA, $calls[0][0]);
        $this->assertEquals(pack('V', strlen($data)), $calls[0][1]);

        // 2-3. CMD_DATA chunks, each no larger than MAX_CHUNK_SIZE, concatenating back to the original data
        $this->assertEquals(Util::CMD_DATA, $calls[1][0]);
        $this->assertEquals(Util::MAX_CHUNK_SIZE, strlen($calls[1][1]));
        $this->assertEquals(Util::CMD_DATA, $calls[2][0]);
        $this->assertEquals(200, strlen($calls[2][1]));
        $this->assertEquals($data, $calls[1][1] . $calls[2][1]);

        // 4. The real command is finally issued with an empty string, since the data is already buffered
        $this->assertEquals(999, $calls[3][0]);
        $this->assertEquals('', $calls[3][1]);
    }

    public function testReturnsFalseIfAnyStepFails()
    {
        $data = str_repeat('A', Util::MAX_CHUNK_SIZE + 1);

        $zk = $this->getMockBuilder(ZKTeco::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['_command'])
            ->getMock();

        $zk->method('_command')->willReturn(false);

        $result = Util::sendWithBuffer($zk, 999, $data);

        $this->assertFalse($result);
    }
}
