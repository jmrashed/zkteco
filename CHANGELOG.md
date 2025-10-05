# Change log
All notable changes to `jmrashed/zkteco` will be documented in this file

## Version 1.3.0 at 5 October 2025
### What's Changed
#### Bug Fixes
- **FIXED**: enrollFingerprint method now properly handles raw template data from getFingerprint()
- **FIXED**: Resolved "unpack(): Type H: not enough input" error when transferring fingerprint templates between devices
- **FIXED**: Template data corruption issue caused by double header addition
- **IMPROVED**: Enhanced template data validation and header detection
- **IMPROVED**: Better error handling for fingerprint enrollment operations

#### Technical Improvements
- Enhanced Fingerprint::enroll() method with intelligent header detection
- Added validation for existing template headers before processing
- Improved compatibility for transferring templates between different ZKTeco devices
- Better handling of both raw and parsed fingerprint template data

## Version 1.2.0 at 1 October 2025
### What's Changed
#### Advanced Device Management Features
- **NEW**: Real-time Event Monitoring - Monitor device events like attendance punches, door events, and system alerts
- **NEW**: Door Control Functions - Remotely open, close, lock, and unlock doors with status monitoring
- **NEW**: Custom LCD Message Display - Send custom formatted messages to device LCD screen with duration control
- **NEW**: Time Zone Synchronization - Automatically sync device time with server timezone
- **NEW**: Event Handler System - Advanced event handling with callback registration and filtering
- **NEW**: Door Status Monitoring - Real-time door status including lock state, sensor status, and alarms
- **NEW**: Event Monitor Class - Dedicated class for managing real-time event subscriptions
- **IMPROVED**: Enhanced device communication with better error handling
- **ADDED**: Comprehensive test suite for device management features

#### Technical Improvements
- Enhanced Device helper class with advanced control functions
- Added EventMonitor helper class for event management
- New constants for door actions and event types
- Improved real-time communication protocols
- Enhanced LCD message formatting and duration control
- Production-ready event polling and parsing

## Version 1.1.0 at 1 October 2025
### What's Changed
#### Enhanced User Management Features
- **NEW**: Parse and Set Fingerprint Data - Enhanced fingerprint template parsing with quality assessment
- **NEW**: Get/Set User Face Data - Complete face recognition template management
- **NEW**: Retrieve User Card Number - Dedicated method to fetch user card numbers
- **NEW**: Advanced User Role Management - Granular role control with permission system
- **NEW**: Fingerprint Template Enrollment - Production-ready fingerprint enrollment system
- **NEW**: Face Template Enrollment - Face recognition template enrollment capabilities
- **NEW**: Quality Score Calculation - Template quality assessment for both fingerprint and face data
- **NEW**: Role Permission System - Comprehensive permission management for different user roles
- **IMPROVED**: Enhanced error handling and validation
- **ADDED**: Comprehensive test suite for all new features
- **ADDED**: PHPUnit configuration for automated testing

#### Technical Improvements
- Enhanced Fingerprint helper class with template parsing and enrollment
- Enhanced Face helper class with complete template management
- Enhanced User helper class with advanced role and card management
- Added quality assessment algorithms for biometric templates
- Improved code documentation and type hints
- Production-ready error handling and validation

## Version 1.0.0 at 22 April 2024
### What's Changed
- Initial Release V1.0.0

