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
        $this->zk = $this->createMock(ZKTeco::class);
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
        $this->zk->method('getUser')->willReturn([
            'user1' => [
                'uid' => $this->testUid,
                'cardno' => '1234567890'
            ]
        ]);
        
        $result = $this->zk->getUserCardNumber($this->testUid);
        
        $this->assertEquals('1234567890', $result);
    }

    public function testSetUserRole()
    {
        $this->zk->method('getUser')->willReturn([
            'user1' => [
                'uid' => $this->testUid,
                'userid' => 'user1',
                'name' => 'Test User',
                'password' => '1234',
                'cardno' => '1234567890'
            ]
        ]);
        
        $this->zk->method('setUser')->willReturn(true);
        
        $result = $this->zk->setUserRole($this->testUid, Util::LEVEL_ADMIN);
        
        $this->assertTrue($result);
    }

    public function testGetUserRole()
    {
        $this->zk->method('getUser')->willReturn([
            'user1' => [
                'uid' => $this->testUid,
                'role' => Util::LEVEL_ADMIN
            ]
        ]);
        
        $result = $this->zk->getUserRole($this->testUid);
        
        $this->assertIsArray($result);
        $this->assertEquals(Util::LEVEL_ADMIN, $result['role_id']);
        $this->assertEquals('Admin', $result['role_name']);
        $this->assertTrue($result['can_enroll']);
        $this->assertTrue($result['can_manage_users']);
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
        $result = $this->zk->parseFingerprintTemplate('invalid');
        
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
        $this->zk->method('getUser')->willReturn([]);
        
        $result = $this->zk->getUserCardNumber(99999);
        
        $this->assertFalse($result);
    }

    public function testSetRoleUserNotFound()
    {
        $this->zk->method('getUser')->willReturn([]);
        
        $result = $this->zk->setUserRole(99999, Util::LEVEL_ADMIN);
        
        $this->assertFalse($result);
    }
}