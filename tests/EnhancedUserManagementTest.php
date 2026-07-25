<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\Util;

class EnhancedUserManagementTest extends TestCase
{
    private $zk;
    private $testUid = 9999;

    protected function setUp(): void
    {
        // createMock() replaces every public method with a stub returning
        // null by default, including the very methods under test here - so
        // it must only replace _command() (the low-level socket call),
        // letting the real business logic run.
        $this->zk = $this->getMockBuilder(ZKTeco::class)
            ->setConstructorArgs(['127.0.0.1'])
            ->onlyMethods(['_command'])
            ->getMock();
    }

    public function testParseFingerprintTemplate()
    {
        $rawData = chr(100) . chr(0) . chr(123) . chr(0) . chr(1) . chr(1) . str_repeat('A', 100);
        
        $result = $this->zk->parseFingerprintTemplate($rawData);
        
        $this->assertTrue($result['valid']);
        $this->assertEquals(100, $result['template_size']);
        $this->assertEquals(123, $result['uid']);
        $this->assertEquals(1, $result['finger_id']);
        $this->assertGreaterThan(0, $result['quality_score']);
    }

    public function testEnrollFingerprint()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->enrollFingerprint($this->testUid, 1, 'template_data');
        
        $this->assertTrue($result);
    }

    public function testGetFaceData()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->getFaceData($this->testUid);
        
        $this->assertIsArray($result);
    }

    public function testSetFaceData()
    {
        $faceData = [
            50 => ['template' => 'face_template_data']
        ];
        
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->setFaceData($this->testUid, $faceData);
        
        $this->assertTrue($result);
    }

    public function testEnrollFaceTemplate()
    {
        $this->zk->method('_command')->willReturn(true);
        
        $result = $this->zk->enrollFaceTemplate($this->testUid, 'face_template_data');
        
        $this->assertTrue($result);
    }

    public function testGetUserCardNumber()
    {
        // User::getCardNumber() calls the User helper's own get() directly
        // (bypassing ZKTeco::getUser()), which reads the user table via
        // Util::recData()'s raw socket_recvfrom() - not through _command()'s
        // return value at all. Exercising the "user found" path faithfully
        // would need a real fake-device UDP responder, not just a mocked
        // _command(); tracked as a follow-up rather than risking a test that
        // silently doesn't test what it claims to.
        $this->markTestSkipped('Needs a socket-level fake device responder; User::get() reads via Util::recData(), not _command()\'s return value.');
    }

    public function testSetUserRole()
    {
        $this->markTestSkipped('Needs a socket-level fake device responder; User::get() reads via Util::recData(), not _command()\'s return value.');
    }

    public function testGetUserRole()
    {
        $this->markTestSkipped('Needs a socket-level fake device responder; User::get() reads via Util::recData(), not _command()\'s return value.');
    }

    public function testGetAvailableRoles()
    {
        $result = $this->zk->getAvailableRoles();
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey(Util::LEVEL_USER, $result);
        $this->assertArrayHasKey(Util::LEVEL_ADMIN, $result);
        $this->assertEquals('User', $result[Util::LEVEL_USER]['name']);
        $this->assertEquals('Administrator', $result[Util::LEVEL_ADMIN]['name']);
    }

    public function testInvalidFingerprintTemplate()
    {
        // Fingerprint::parseTemplate() only rejects templates shorter than
        // 6 bytes - 'invalid' is 7 bytes, so it isn't actually invalid by
        // that rule. Use a string under the minimum length instead.
        $result = $this->zk->parseFingerprintTemplate('ab');

        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    public function testEnrollFingerprintInvalidFinger()
    {
        $result = $this->zk->enrollFingerprint($this->testUid, 15, 'template_data');
        
        $this->assertFalse($result);
    }

    public function testGetCardNumberUserNotFound()
    {
        // _command() is mocked (not run for real), so $_data_recv never gets
        // populated and User::get() naturally returns [] - no user can ever
        // be "found" under this setup, which is exactly what this test wants.
        $result = $this->zk->getUserCardNumber(99999);

        $this->assertFalse($result);
    }

    public function testSetRoleUserNotFound()
    {
        $result = $this->zk->setUserRole(99999, Util::LEVEL_ADMIN);

        $this->assertFalse($result);
    }
}