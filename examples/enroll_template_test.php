<?php

require __DIR__ . '/../vendor/autoload.php';

use Jmrashed\Zkteco\Lib\ZKTeco;

// Simple CLI usage check
if ($argc < 5) {
    echo "Usage: php enroll_template_test.php <device_ip> <uid> <finger_id> <template_file>\n";
    echo "Example: php enroll_template_test.php 192.168.200.211 8888 0 finger_template.bin\n";
    exit(1);
}

$ip = $argv[1];
$uid = (int)$argv[2];
$fingerId = (int)$argv[3];
$templateFile = $argv[4];

if (!file_exists($templateFile)) {
    echo "Template file not found: $templateFile\n";
    exit(1);
}

$templateData = file_get_contents($templateFile);
if ($templateData === false || strlen($templateData) === 0) {
    echo "Failed to read template file or file is empty.\n";
    exit(1);
}

echo "Connecting to device $ip...\n";
$zk = new ZKTeco($ip);
if (! $zk->connect()) {
    echo "Cannot connect to device at $ip\n";
    exit(1);
}

echo "Disabling device to upload template...\n";
$zk->disableDevice();

echo "Enrolling fingerprint for UID=$uid fingerId=$fingerId...\n";
$ok = $zk->enrollFingerprint($uid, $fingerId, $templateData);

$zk->enableDevice();
$zk->disconnect();

if ($ok) {
    echo "Success: fingerprint enrolled for UID={$uid} finger={$fingerId}\n";
    exit(0);
} else {
    echo "Failed to enroll fingerprint.\n";
    exit(2);
}
