<img src="./src/Assets/header.png">

![Packagist Downloads](https://img.shields.io/packagist/dt/jmrashed/zkteco)
![GitHub stars](https://img.shields.io/github/stars/jmrashed/zkteco)
![GitHub forks](https://img.shields.io/github/forks/jmrashed/zkteco)
![License](https://img.shields.io/github/license/jmrashed/zkteco)
![Latest Stable Version](https://img.shields.io/packagist/v/jmrashed/zkteco)
![GitHub issues](https://img.shields.io/github/issues/jmrashed/zkteco)
![GitHub closed issues](https://img.shields.io/github/issues-closed/jmrashed/zkteco)
![GitHub pull requests](https://img.shields.io/github/issues-pr/jmrashed/zkteco)
![GitHub closed pull requests](https://img.shields.io/github/issues-pr-closed/jmrashed/zkteco)





This Laravel package provides convenient functions for interacting with ZKTeco devices, allowing seamless communication with attendance devices (such as fingerprint, face recognition, or RFID) using UDP protocol. It simplifies the process of reading and writing data directly to these devices from a web server without the need for additional programs.

With this package, you can easily perform various activities with ZKTeco devices, such as retrieving attendance logs, setting user data, enabling or disabling device functions, and more, all within your Laravel application.

Designed as a class-based library, you can simply create an object of the provided class and utilize its functions to interact with ZKTeco devices effortlessly.

Key features include:

- Reading and writing data to attendance devices using UDP protocol.
- Seamless communication between web servers and attendance devices.
- Simplified implementation for activities such as retrieving attendance logs, setting user data, and managing device functions.
- Integration with Laravel framework for easy usage and compatibility.


Experience streamlined communication and management of ZKTeco devices directly from your Laravel application with this ZKTeco Laravel package.

 

The `jmrashed/zkteco` package provides easy to use functions to ZKTeco Device activities.

## Prerequisites

- PHP installed on your system
- Access to the `Jmrashed\Zkteco\Lib\ZKTeco` class
- Knowledge of the ZKTeco device IP address and port (if different from the default)

## Installation

To use the ZKTeco library, you need to include it in your PHP project. You can install it via Composer:
```bash
composer require jmrashed/zkteco
```

## Enabling PHP Sockets

This guide outlines the steps to enable PHP sockets on your server. Sockets are essential for establishing communication channels between different processes or computers over a network.

### Prerequisites

- PHP installed on your server
- Access to the `php.ini` configuration file
- Basic knowledge of server administration

### Steps

1. **Check PHP Installation**: Verify that PHP is installed on your server by running `php -v` in your terminal or command prompt.

2. **Enable Sockets Extension**: Edit the `php.ini` file to enable the sockets extension. Find the following line:

```ini
;extension=sockets
```

Remove the semicolon at the beginning of the line to uncomment it:

```ini
    extension=sockets
```

3. **Restart Web Server**: After editing `php.ini`, restart your web server to apply the changes. Use the appropriate command based on your server software (e.g., Apache, Nginx).

4. **Verify Installation**: Create a PHP file (e.g., `test.php`) with the following contents:

    ```php
    <?php
    if (function_exists('socket_create')) {
        echo "Sockets extension is enabled.";
    } else {
        echo "Sockets extension is not enabled.";
    }
    ```

    Access this file through your web browser to verify that the sockets extension is enabled.

5. **Firewall Configuration (if necessary)**: If you're using a firewall, ensure it allows connections on the socket port (default: 80).

6. **Test Socket Communication**: Implement socket communication logic in your PHP scripts to test the functionality. See the provided PHP example for creating a socket connection.

### Example Usage

Include the steps outlined in this guide in your server setup documentation to ensure proper configuration and enable PHP sockets for your applications.



## Usage of ZKTeco

1. The provided PHP code snippet seems to demonstrate the usage of the ZKTeco library, specifically for interacting with ZKTeco devices, likely for biometric attendance or access control systems. Let's elaborate on the code:

-   **Importing the ZKTeco Library:**
```php
use Jmrashed\Zkteco\Lib\ZKTeco;
```
This line imports the ZKTeco class from the Jmrashed\Zkteco\Lib namespace. This class likely contains the functionality to communicate with ZKTeco devices.
-   **Instantiating the ZKTeco Object::**
```php
$zk = new ZKTeco('192.168.1.201');
```
Here, an instance of the `ZKTeco` class is created with the IP address `192.168.1.201`. This likely establishes a connection to the `ZKTeco` device at that IP address. Optionally, you can specify the port number as the second parameter if it's different from the default port `4370`.

-   **Alternative Instantiation with Port::**
```php
// Using IP address and port
$zk = new ZKTeco('192.168.1.201', 8080);
```
This is an alternative way to instantiate the `ZKTeco` object, where both the IP address (`192.168.1.201`) and port number (`8080`) are provided explicitly.

 
# Calling ZKTeco Methods

## 1. Connect to Device
```php
// Connect to the ZKTeco device
// Returns a boolean indicating whether the connection was successful
$connected = $zk->connect();
```
## 2. Disconnect from Device
```php
// Disconnect from the ZKTeco device
// Returns a boolean indicating whether the disconnection was successful
$disconnected = $zk->disconnect();
```
## 3. Enable Device
```php
// Enable the ZKTeco device
// Returns a boolean or mixed value indicating whether the device was enabled
// Note: This method should be called after reading/writing any device information
$enabled = $zk->enableDevice();
```
Note: It's important to call the enableDevice() method after any read or write operation on the device.
## 4. Disable Device
```php
// Disable the ZKTeco device
// Returns a boolean or mixed value indicating whether the device was disabled
// Note: This method should be called before reading or writing any device information
$disabled = $zk->disableDevice();
```

## 5. Device Version
```php
// Get the firmware version of the ZKTeco device
// Returns a boolean or mixed value containing the device version information
$version = $zk->version();
```


## 6. Device OS Version
```php
// Get the operating system version of the ZKTeco device
// Returns a boolean or mixed value containing the device OS version information
$osVersion = $zk->osVersion(); 
```


## 7. Power Off
```php
// Turn off the ZKTeco device
// Returns a boolean or mixed value indicating whether the device shutdown was successful
$shutdown = $zk->shutdown();
```


## 8. Restart
```php
// Restart the ZKTeco device
// Returns a boolean or mixed value indicating whether the device restart was successful
$restart = $zk->restart();
```

## 9. Sleep
```php
// Put the ZKTeco device into sleep mode
// Returns a boolean or mixed value indicating whether the device entered sleep mode
$sleep = $zk->sleep();
```

## 10. Resume
```php
// Resume the ZKTeco device from sleep mode
// Returns a boolean or mixed value indicating whether the device resumed from sleep mode
$resume = $zk->resume();
```

## 11. Voice Test
```php
// Test the voice functionality of the ZKTeco device by saying "Thank you"
// Returns a boolean or mixed value indicating whether the voice test was successful
$voiceTest = $zk->testVoice();
```


## 12. Platform
```php
// Get the platform information of the ZKTeco device
// Returns a boolean or mixed value containing the platform information
$platform = $zk->platform();
```


## 13. Firmware Version
```php
// Get the firmware version of the ZKTeco device
// Returns a boolean or mixed value containing the firmware version information
$fmVersion = $zk->fmVersion();
```




## 14. Work Code
```php
// Get the work code information of the ZKTeco device
// Returns a boolean or mixed value containing the work code information
$workCode = $zk->workCode(); 

```



## 15. Device Name
```php
// Get the name of the ZKTeco device
// Returns a boolean or mixed value containing the device name information
$deviceName = $zk->deviceName(); 

```


## 16. Get Device Time
```php
// Get the current time of the ZKTeco device
// Returns a boolean or mixed value containing the device time information
// Format: "Y-m-d H:i:s"
$deviceTime = $zk->getTime();

```


## 17. Set Device Time
```php
// Set the time of the ZKTeco device
// Parameters:
// - string $t: Time string in format "Y-m-d H:i:s"
// Returns a boolean or mixed value indicating whether the device time was successfully set
$setTimeResult = $zk->setTime($timeString);
```


## 18. Get Users
```php
// Get the list of users stored in the ZKTeco device
// Returns an array containing user information
$users = $zk->getUser();
```

## 19. Set Users
```php
// Set a user in the ZKTeco device
// Parameters:
// - int $uid: Unique ID (max 65535)
// - int|string $userid: ID in DB (max length = 9, only numbers - depends device setting)
// - string $name: User name (max length = 24)
// - int|string $password: Password (max length = 8, only numbers - depends device setting)
// - int $role: User role (default Util::LEVEL_USER)
// - int $cardno: Card number (default 0, max length = 10, only numbers)
// Returns a boolean or mixed value indicating whether the user was successfully set
$setUserResult = $zk->setUser($uid, $userid, $name, $password, $role, $cardno);
```

## 20. Clear All Admin
```php
// Remove all admin users from the ZKTeco device
// Returns a boolean or mixed value indicating whether all admin users were successfully removed
$clearedAdmin = $zk->clearAdmin();
```

## 21. Clear All Users
```php
// Remove all users from the ZKTeco device
// Returns a boolean or mixed value indicating whether all users were successfully removed
$clearedUsers = $zk->clearUsers();
```

## 22. Remove A User
```php
// Remove a user from the ZKTeco device by UID
// Parameters:
// - integer $uid: User ID to remove
// Returns a boolean or mixed value indicating whether the user was successfully removed
$removedUser = $zk->removeUser($uid);
```

## 23. Get Attendance Log
```php
// Get the attendance log from the ZKTeco device
// Returns an array containing attendance log information
// Each entry in the array represents a single attendance record with fields: uid, id, state, timestamp, and type
$attendanceLog = $zk->getAttendance();
```

## 24. Get Todays Attendance Log

### 24.1 getTodaysRecords()
```php
// Get the today attendance log from the ZKTeco device
// Returns an array containing attendance log information
// Each entry in the array represents a single attendance record with fields: uid, id, state, timestamp, and type
$attendanceLog = $zk->getTodaysRecords();

```
### Sample Response Example 
```json
  array (
    'uid' => 33,
    'id' => '108',
    'state' => 1,
    'timestamp' => '2024-04-24 18:13:47',
    'type' => 1,
  )
  ```

  ### 24.2 Get today's Records
  ```php
    public function zkteco()
    {
        $zk = new ZKTeco('192.168.1.201');
        $connected = $zk->connect();
        $attendanceLog = $zk->getAttendance();

        // Get today's date
        $todayDate = date('Y-m-d');

        // Filter attendance records for today
        $todayRecords = [];
        foreach ($attendanceLog as $record) {
            // Extract the date from the timestamp
            $recordDate = substr($record['timestamp'], 0, 10);

            // Check if the date matches today's date
            if ($recordDate === $todayDate) {
                $todayRecords[] = $record;
            }
        }

        // Now $todayRecords contains attendance records for today
        Log::alert($todayRecords); 
    }
```
### 24.3 Get Latest Attendance with Limit
```php
// Get the 5 latest attendance records
$latestAttendance = $zk->getAttendance(5);
```

## 24.4 Clear Attendance Log
```php
// Clear the attendance log from the ZKTeco device
// Returns a boolean or mixed value indicating whether the attendance log was successfully cleared
$clearedAttendance = $zk->clearAttendance();
```

# Enhanced User Management Features

## 25. Parse Fingerprint Template Data
```php
// Parse raw fingerprint template data into structured format
// Returns array with template metadata and quality score
$parsedTemplate = $zk->parseFingerprintTemplate($rawTemplateData);

// Example response:
// [
//     'valid' => true,
//     'template_size' => 512,
//     'uid' => 123,
//     'finger_id' => 1,
//     'flag' => 1,
//     'template_data' => '...',
//     'quality_score' => 85
// ]
```

## 26. Enroll Fingerprint Template
```php
// Enroll a new fingerprint template for a user
// Parameters: uid, finger_id (0-9), template_data
$enrolled = $zk->enrollFingerprint(123, 1, $templateData);
```

## 27. Get User Face Data
```php
// Retrieve face recognition templates for a user
// Returns array of face templates with quality scores
$faceData = $zk->getFaceData(123);

// Example response:
// [
//     50 => [
//         'template' => '...',
//         'size' => 1024,
//         'quality' => 92
//     ]
// ]
```

## 28. Set User Face Data
```php
// Set face recognition templates for a user
$faceData = [
    50 => ['template' => $faceTemplateData]
];
$result = $zk->setFaceData(123, $faceData);
```

## 29. Enroll Face Template
```php
// Enroll a face recognition template for a user
// Automatically finds available slot
$enrolled = $zk->enrollFaceTemplate(123, $faceTemplateData);
```

## 30. Get User Card Number
```php
// Retrieve the card number for a specific user
$cardNumber = $zk->getUserCardNumber(123);
// Returns: "1234567890" or false if not found
```

## 31. Advanced User Role Management

### 31.1 Set User Role with Permissions
```php
// Set advanced user role with granular permissions
$permissions = ['attendance', 'reports', 'user_management'];
$result = $zk->setUserRole(123, Util::LEVEL_ADMIN, $permissions);
```

### 31.2 Get User Role Information
```php
// Get detailed role information for a user
$roleInfo = $zk->getUserRole(123);

// Example response:
// [
//     'role_id' => 14,
//     'role_name' => 'Administrator',
//     'permissions' => ['all_access', 'user_management', 'system_config'],
//     'can_enroll' => true,
//     'can_manage_users' => true,
//     'can_view_logs' => true
// ]
```

### 31.3 Get Available Roles
```php
// Get all available user roles and their descriptions
$availableRoles = $zk->getAvailableRoles();

// Example response:
// [
//     0 => [
//         'name' => 'User',
//         'description' => 'Standard user with basic access',
//         'permissions' => ['attendance', 'view_own_records']
//     ],
//     14 => [
//         'name' => 'Administrator', 
//         'description' => 'Full administrative access',
//         'permissions' => ['all_access', 'user_management', 'system_config']
//     ]
// ]
```

## 32. Quality Assessment

### 32.1 Fingerprint Quality
```php
// Get fingerprint template with quality assessment
$fingerprints = $zk->getFingerprint(123);
foreach ($fingerprints as $fingerId => $template) {
    $parsed = $zk->parseFingerprintTemplate($template);
    echo "Finger {$fingerId} quality: {$parsed['quality_score']}%";
}
```

### 32.2 Face Template Quality
```php
// Get face templates with quality scores
$faceData = $zk->getFaceData(123);
foreach ($faceData as $faceId => $data) {
    echo "Face template {$faceId} quality: {$data['quality']}%";
}
```

# Advanced Device Management Features

## 33. Custom LCD Message Display

### 33.1 Display Custom Message
```php
// Display custom message on LCD screen
// Parameters: message, line (1-4), duration in seconds (0 = permanent)
$result = $zk->displayCustomMessage('Welcome John!', 1, 10);
```

### 33.2 Display Permanent Message
```php
// Display permanent message (until manually cleared)
$result = $zk->displayCustomMessage('System Maintenance', 2, 0);
```

### 33.3 Clear LCD Screen
```php
// Clear all LCD content
$result = $zk->clearLCD();
```

## 34. Door Control Functions

### 34.1 Open Door
```php
// Open door remotely
$result = $zk->openDoor(1); // Door ID 1
```

### 34.2 Close Door
```php
// Close door remotely
$result = $zk->closeDoor(1);
```

### 34.3 Lock Door
```php
// Lock door remotely
$result = $zk->lockDoor(1);
```

### 34.4 Unlock Door
```php
// Unlock door remotely
$result = $zk->unlockDoor(1);
```

### 34.5 Get Door Status
```php
// Get current door status
$status = $zk->getDoorStatus(1);

// Example response:
// [
//     'door_open' => true,
//     'door_locked' => false,
//     'sensor_active' => true,
//     'alarm_active' => false,
//     'timestamp' => '2024-10-01 15:30:45'
// ]
```

## 35. Time Zone Synchronization

### 35.1 Sync with Server Timezone
```php
// Sync device time with server's default timezone
$result = $zk->syncTimeZone();
```

### 35.2 Sync with Custom Timezone
```php
// Sync device time with specific timezone
$result = $zk->syncTimeZone('America/New_York');
$result = $zk->syncTimeZone('Europe/London');
$result = $zk->syncTimeZone('Asia/Tokyo');
```

## 36. Real-time Event Monitoring

### 36.1 Get Real-time Events
```php
// Get real-time events with timeout
$events = $zk->getRealTimeEvents(30); // 30 seconds timeout

// Example response:
// [
//     [
//         'type' => 'attendance',
//         'uid' => 123,
//         'timestamp' => '2024-10-01 15:30:45',
//         'state' => 1,
//         'raw_data' => '...' 
//     ]
// ]
```

### 36.2 Start Event Monitoring with Callback
```php
// Start monitoring with custom callback
$callback = function($event) {
    echo "Event: {$event['type']} - User: {$event['uid']} - Time: {$event['timestamp']}\n";
    
    // Handle different event types
    switch($event['type']) {
        case 'attendance':
            // Process attendance event
            break;
        case 'door_open':
            // Handle door open event
            break;
        case 'alarm':
            // Handle alarm event
            break;
    }
};

// Start monitoring (runs for 60 seconds)
$zk->startEventMonitoring($callback, 60);
```

### 36.3 Advanced Event Monitoring
```php
// Get event monitor instance for advanced handling
$monitor = $zk->getEventMonitor();

// Register specific event handlers
$monitor->on('attendance', function($event) {
    echo "Attendance event for user {$event['uid']}\n";
});

$monitor->on('door_open', function($event) {
    echo "Door opened by user {$event['uid']}\n";
});

$monitor->on('alarm', function($event) {
    echo "ALARM: {$event['timestamp']}\n";
    // Send notification, log to database, etc.
});

// Register handler for all events
$monitor->on('*', function($event) {
    // Log all events to database
    logEventToDatabase($event);
});

// Start monitoring
$monitor->start(); // Runs indefinitely

// Stop monitoring
$monitor->stop();
```

### 36.4 Stop Event Monitoring
```php
// Stop real-time event monitoring
$result = $zk->stopEventMonitoring();
```

## 37. Event Types

The system supports various event types:

- **attendance**: User attendance punch (check-in/out)
- **door_open**: Door opened event
- **door_close**: Door closed event  
- **alarm**: Security alarm triggered
- **user_enroll**: New user enrolled
- **user_delete**: User deleted
- **system_start**: Device started/rebooted
- **system_shutdown**: Device shutdown

## 38. Door Control Constants

```php
// Door action constants
Util::DOOR_ACTION_OPEN    // Open door
Util::DOOR_ACTION_CLOSE   // Close door
Util::DOOR_ACTION_LOCK    // Lock door
Util::DOOR_ACTION_UNLOCK  // Unlock door

// Event type constants
Util::EVENT_TYPE_ATTENDANCE      // Attendance event
Util::EVENT_TYPE_DOOR_OPEN       // Door open event
Util::EVENT_TYPE_DOOR_CLOSE      // Door close event
Util::EVENT_TYPE_ALARM           // Alarm event
Util::EVENT_TYPE_USER_ENROLL     // User enrollment event
Util::EVENT_TYPE_USER_DELETE     // User deletion event
Util::EVENT_TYPE_SYSTEM_START    // System start event
Util::EVENT_TYPE_SYSTEM_SHUTDOWN // System shutdown event
```

## 39. Usage Examples

### 39.1 Complete Door Management
```php
// Initialize device
$zk = new ZKTeco('192.168.1.201');
$zk->connect();

// Check door status
$status = $zk->getDoorStatus(1);
if ($status['door_locked']) {
    // Unlock door for authorized access
    $zk->unlockDoor(1);
    $zk->displayCustomMessage('Door Unlocked', 1, 5);
}

// Open door
$zk->openDoor(1);

// Wait and close
sleep(10);
$zk->closeDoor(1);
$zk->lockDoor(1);

$zk->disconnect();
```

### 39.2 Real-time Monitoring System
```php
$zk = new ZKTeco('192.168.1.201');
$zk->connect();

$monitor = $zk->getEventMonitor();

// Set up event handlers
$monitor->on('attendance', function($event) {
    // Log attendance to database
    $user = getUserById($event['uid']);
    logAttendance($user, $event['timestamp'], $event['state']);
    
    // Display welcome message
    $zk->displayCustomMessage("Welcome {$user['name']}!", 1, 3);
});

$monitor->on('alarm', function($event) {
    // Send alert notifications
    sendSecurityAlert($event);
    
    // Display alarm message
    $zk->displayCustomMessage('SECURITY ALERT!', 1, 0);
});

// Start monitoring
$monitor->start();
```
# Change log
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

# Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

# Security
If you discover any security-related issues, please email `jmrashed@gmail.com` instead of using the issue tracker.

# License
The [MIT License (MIT)](LICENSE.md). Please see License File for more information.



# Conclusion
This guide covers various methods provided by the ZKTeco library in PHP for interacting with ZKTeco devices. You can use these methods to perform various operations such as device management, user management, attendance tracking, and more.

