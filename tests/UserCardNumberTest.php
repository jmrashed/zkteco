<?php

namespace Tests;

use Jmrashed\Zkteco\Lib\Helper\User;
use Jmrashed\Zkteco\Lib\ZKTeco;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserCardNumberTest extends TestCase
{
    #[DataProvider('cardNumberProvider')]
    public function testSetEncodesCardNumberAsLittleEndianUint32(int $card): void
    {
        $payload = null;

        $zk = $this->createMock(ZKTeco::class);
        $zk->expects($this->once())
            ->method('_command')
            ->willReturnCallback(function ($command, $commandString) use (&$payload) {
                $payload = $commandString;

                return true;
            });

        $this->assertTrue(User::set($zk, 1, '101', 'Test', '', 0, $card));

        $this->assertNotNull($payload);
        $this->assertSame($card, unpack('V', substr($payload, 35, 4))[1]);
    }

    public static function cardNumberProvider(): array
    {
        return [
            'odd-length hex (1016618 => f832a)' => [1016618],
            '33578 (0x832a)' => [33578],
            '65535 (0xffff)' => [65535],
            '65536 (0x10000)' => [65536],
            '4294967295 (0xffffffff)' => [4294967295],
        ];
    }
}
