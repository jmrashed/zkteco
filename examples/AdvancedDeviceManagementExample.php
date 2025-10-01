<?php

require_once '../vendor/autoload.php';

use Jmrashed\Zkteco\Lib\ZKTeco;
use Jmrashed\Zkteco\Lib\Helper\Util;

/**
 * Advanced Device Management Examples
 * 
 * This file demonstrates the usage of advanced device management features
 * including real-time event monitoring, door control, LCD messaging, and timezone sync.
 */

// Initialize ZKTeco device
$zk = new ZKTeco('192.168.1.201');

if (!$zk->connect()) {
    die("Failed to connect to device\n");
}

echo "Connected to ZKTeco device successfully!\n";

// Example 1: Custom LCD Message Display
echo "\n=== LCD Message Display Examples ===\n";

// Display welcome message
$zk->displayCustomMessage('Welcome to Office!', 1, 0);
echo "Displayed welcome message on line 1\n";

// Display temporary message
$zk->displayCustomMessage('System Update: 15:30', 2, 10);
echo "Displayed temporary message on line 2 (10 seconds)\n";

// Display multi-line information
$zk->displayCustomMessage('Temperature: 24°C', 3, 0);
$zk->displayCustomMessage('Humidity: 65%', 4, 0);
echo "Displayed environmental info on lines 3-4\n";

// Example 2: Door Control Functions
echo "\n=== Door Control Examples ===\n";

// Check door status
$doorStatus = $zk->getDoorStatus(1);
echo "Door Status: " . json_encode($doorStatus, JSON_PRETTY_PRINT) . "\n";

// Unlock and open door
if ($zk->unlockDoor(1)) {
    echo "Door unlocked successfully\n";
    $zk->displayCustomMessage('Door Unlocked', 1, 3);
    
    if ($zk->openDoor(1)) {
        echo "Door opened successfully\n";
        
        // Wait 5 seconds then close and lock
        sleep(5);
        
        if ($zk->closeDoor(1)) {
            echo "Door closed successfully\n";
            
            if ($zk->lockDoor(1)) {
                echo "Door locked successfully\n";
                $zk->displayCustomMessage('Door Secured', 1, 3);
            }
        }
    }
}

// Example 3: Time Zone Synchronization
echo "\n=== Time Zone Synchronization Examples ===\n";

// Get current device time
$currentTime = $zk->getTime();
echo "Current device time: $currentTime\n";

// Sync with server timezone
if ($zk->syncTimeZone()) {
    echo "Device time synchronized with server timezone\n";
    $newTime = $zk->getTime();
    echo "New device time: $newTime\n";
}

// Sync with specific timezone
if ($zk->syncTimeZone('America/New_York')) {
    echo "Device time synchronized with New York timezone\n";
}

// Example 4: Real-time Event Monitoring
echo "\n=== Real-time Event Monitoring Examples ===\n";

// Simple event monitoring with callback
echo "Starting simple event monitoring for 30 seconds...\n";

$eventCallback = function($event) {
    echo "Event received: " . json_encode($event, JSON_PRETTY_PRINT) . "\n";
    
    switch($event['type']) {
        case 'attendance':
            echo "User {$event['uid']} punched at {$event['timestamp']}\n";
            break;
        case 'door_open':
            echo "Door opened by user {$event['uid']} at {$event['timestamp']}\n";
            break;
        case 'alarm':
            echo "SECURITY ALARM at {$event['timestamp']}!\n";
            break;
    }
};

// Monitor events for 30 seconds
$zk->startEventMonitoring($eventCallback, 30);

// Example 5: Advanced Event Monitoring with Event Monitor
echo "\n=== Advanced Event Monitoring Examples ===\n";

$monitor = $zk->getEventMonitor();

// Register specific event handlers
$monitor->on('attendance', function($event) use ($zk) {
    echo "Attendance Event - User: {$event['uid']}, Time: {$event['timestamp']}\n";
    
    // Display welcome message on LCD
    $zk->displayCustomMessage("Welcome User {$event['uid']}", 1, 5);
    
    // You could also:
    // - Log to database
    // - Send notifications
    // - Update attendance records
});

$monitor->on('door_open', function($event) use ($zk) {
    echo "Door Open Event - User: {$event['uid']}, Time: {$event['timestamp']}\n";
    
    // Display door status
    $zk->displayCustomMessage('Door Opened', 2, 3);
});

$monitor->on('alarm', function($event) use ($zk) {
    echo "ALARM EVENT - Time: {$event['timestamp']}\n";
    
    // Display alarm message
    $zk->displayCustomMessage('SECURITY ALERT!', 1, 0);
    
    // You could also:
    // - Send email/SMS alerts
    // - Trigger security protocols
    // - Log security incidents
});

// Register handler for all events
$monitor->on('*', function($event) {
    // Log all events to a file or database
    file_put_contents('device_events.log', 
        date('Y-m-d H:i:s') . " - " . json_encode($event) . "\n", 
        FILE_APPEND
    );
});

echo "Starting advanced event monitoring for 60 seconds...\n";
echo "Registered handlers for attendance, door_open, alarm, and all events\n";

// Start monitoring for 60 seconds
$monitor->start(60);

// Example 6: Complete Security System Integration
echo "\n=== Complete Security System Example ===\n";

function securitySystemExample($zk) {
    // Initialize security system
    $zk->displayCustomMessage('Security System Active', 1, 0);
    
    $monitor = $zk->getEventMonitor();
    
    // Attendance tracking
    $monitor->on('attendance', function($event) use ($zk) {
        $userId = $event['uid'];
        $timestamp = $event['timestamp'];
        
        // Simulate user lookup
        $userName = "User_$userId";
        
        echo "Access granted for $userName at $timestamp\n";
        $zk->displayCustomMessage("Welcome $userName", 1, 5);
        
        // Log attendance
        logAttendance($userId, $timestamp);
    });
    
    // Security monitoring
    $monitor->on('alarm', function($event) use ($zk) {
        echo "SECURITY BREACH DETECTED!\n";
        
        // Display alert
        $zk->displayCustomMessage('SECURITY ALERT!', 1, 0);
        
        // Lock all doors
        $zk->lockDoor(1);
        
        // Send notifications (simulate)
        sendSecurityAlert($event);
    });
    
    // Door monitoring
    $monitor->on('door_open', function($event) use ($zk) {
        // Check if door should be open
        $doorStatus = $zk->getDoorStatus(1);
        
        if (!isAuthorizedAccess($event['uid'])) {
            echo "Unauthorized door access attempt!\n";
            $zk->displayCustomMessage('Unauthorized Access!', 1, 10);
        }
    });
    
    echo "Security system monitoring started...\n";
    $monitor->start(120); // Monitor for 2 minutes
}

// Helper functions (simulate real implementations)
function logAttendance($userId, $timestamp) {
    echo "Logged attendance for user $userId at $timestamp\n";
}

function sendSecurityAlert($event) {
    echo "Security alert sent: " . json_encode($event) . "\n";
}

function isAuthorizedAccess($userId) {
    // Simulate authorization check
    return $userId > 0;
}

// Run security system example
securitySystemExample($zk);

// Cleanup
echo "\n=== Cleanup ===\n";
$zk->clearLCD();
$zk->stopEventMonitoring();
$zk->disconnect();

echo "Disconnected from device. Examples completed!\n";