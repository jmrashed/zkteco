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

