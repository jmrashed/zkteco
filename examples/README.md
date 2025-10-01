# ZKTeco Package Examples

This directory contains practical examples demonstrating the usage of various ZKTeco package features.

## Available Examples

### AdvancedDeviceManagementExample.php

Comprehensive example demonstrating:

- **Custom LCD Message Display**: Show custom messages on device LCD screen
- **Door Control Functions**: Remote door control (open, close, lock, unlock)
- **Time Zone Synchronization**: Sync device time with server timezone
- **Real-time Event Monitoring**: Monitor device events in real-time
- **Advanced Event Handling**: Use event handlers for specific event types
- **Complete Security System**: Full security system integration example

## Running Examples

1. Make sure you have installed the package dependencies:
```bash
composer install
```

2. Update the device IP address in the example files to match your ZKTeco device.

3. Run the examples:
```bash
php examples/AdvancedDeviceManagementExample.php
```

## Prerequisites

- PHP 8.0 or higher
- ZKTeco device connected to network
- PHP sockets extension enabled
- Proper network connectivity to the device

## Device Configuration

Before running examples, ensure:

1. Your ZKTeco device is connected to the network
2. The device IP address is accessible from your server
3. The device is configured to allow UDP communication
4. Firewall settings allow communication on the device port (default: 4370)

## Example Features

### LCD Message Display
- Display welcome messages
- Show temporary notifications
- Multi-line information display
- Automatic message clearing

### Door Control
- Remote door operations
- Door status monitoring
- Security state management
- Access control integration

### Event Monitoring
- Real-time attendance tracking
- Security event detection
- Door activity monitoring
- System event logging

### Time Synchronization
- Server timezone sync
- Custom timezone support
- Automatic time updates
- Time zone conversion

## Customization

You can customize the examples by:

1. Modifying device IP addresses
2. Adjusting timeout values
3. Adding custom event handlers
4. Implementing database logging
5. Adding notification systems

## Troubleshooting

If examples don't work:

1. Check device connectivity: `ping [device_ip]`
2. Verify PHP sockets extension: `php -m | grep socket`
3. Check firewall settings
4. Ensure device is not in sleep mode
5. Verify device firmware compatibility

## Integration Tips

For production use:

1. Implement proper error handling
2. Add database logging
3. Use queues for event processing
4. Implement notification systems
5. Add security validation
6. Use configuration files for settings