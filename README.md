# Traccar GPS server

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mr-wolf-gb/traccar.svg?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar)
[![Total Downloads](https://img.shields.io/packagist/dt/mr-wolf-gb/traccar.svg?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar)
![GitHub Actions](https://github.com/mr-wolf-gb/traccar/actions/workflows/main.yml/badge.svg)
[![Latest Unstable Version](http://poser.pugx.org/mr-wolf-gb/traccar/v/unstable?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar) 
[![License](http://poser.pugx.org/mr-wolf-gb/traccar/license?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar) 
[![PHP Version Require](http://poser.pugx.org/mr-wolf-gb/traccar/require/php?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar)

This Laravel package serves as a seamless integration tool, empowering developers to effortlessly interact with Traccar
servers through their robust API. Traccar, a powerful GPS tracking platform, becomes more accessible than ever as this
package streamlines communication between your Laravel application and the Traccar server, offering a wide range of
functionalities and capabilities. Whether you're retrieving real-time location data, managing devices, or leveraging
advanced tracking features, this package simplifies the process, enhancing the efficiency and extensibility of your
Laravel projects.

## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#features-and-usage">Features and Usage</a>
    - <a href="#multi-users-and-servers">Multi-users and servers</a>
    - <a href="#available-ressources">Available ressources</a>
        - <a href="#server">Server</a>
        - <a href="#session">Session</a>
        - <a href="#user">User</a>
        - <a href="#group">Group</a>
        - <a href="#device">Device</a>
        - <a href="#geofence">Geofence</a>
        - <a href="#notification">Notification</a>
        - <a href="#position">Position</a>
        - <a href="#event">Event</a>
        - <a href="#driver">Driver</a>
        - <a href="#report">Report</a>
- <a href="#traccar-custom-server">Traccar Custom Server and Features</a>
- <a href="#testing">Testing</a>
- <a href="#changelog">Changelog</a>
- <a href="#contributing">Contributing</a>
- <a href="#credits">Credits</a>
- <a href="#license">License</a>

## :wrench: Required PHP version

| Version | Php Version |
|---------|-------------|
| 1.0.0   | ^8.1        |

## Installation

You can install the package via composer:

```bash
composer require mr-wolf-gb/traccar
```

You can publish the config and migration:

```bash
php artisan vendor:publish --provider="MrWolfGb\Traccar\TraccarServiceProvider"
```

Set traccar server information [.env file]:

```dotenv
TRACCAR_BASE_URL="http://localhost:8082/"
TRACCAR_SOCKET_URL="ws://localhost:8082/api/socket"
TRACCAR_USERNAME="admin@traccar.local"
TRACCAR_PASSWORD="password"
TRACCAR_TOKEN="RzBFAiEA84hXSL6uV6FQyBX0_Ds1a6NMcSC..."
# token required only when using fetch session informations
```

## ✨ Features and Usage

#### Multi-users and servers

```php
// by default, it uses .env credentials else you can set it manually
// Inject service as public variable in Controller
public function __construct(public TraccarService $traccarService)
{
    $this->traccarService->setEmail("user1@traccar.local");
    $this->traccarService->setPassword("password");
    $this->traccarService->setBaseUrl("http://localhost:8082/");
    $this->traccarService->setToken("ws://localhost:8082/api/socket");
}
// or inject directly in specific method
public function index(TraccarService $traccarService)
{
    //...
}
```

#### Available ressources

- #### *Server*

Model : **[Server Model](src/Models/Server.php)**

```php
public function index(TraccarService $traccarService)
{
    $serverRepo = $traccarService->serverRepository();
    // Get server information
    $srv = $serverRepo->fetchServerInformation();
    // Update server information
    $serverRepo->updateServerInformation(server: $srv);
}
```

- #### *Session*

Model : **[Session Model](src/Models/Session.php)**

```php
public function index(TraccarService $traccarService)
{
    $sessionRepo = $traccarService->sessionRepository();
    // Create new session 
    $session = $sessionRepo->createNewSession();
    // Get connected session [Require user Token in configuration]
    $session = $sessionRepo->fetchSessionInformation();
    // Close session
    $sessionRepo->closeSession();
}
```

- #### *User*

Model : **[User Model](src/Models/User.php)**

```php
public function index(TraccarService $traccarService)
{
    $userRepo = $traccarService->userRepository();
    // Get list of users
    $list = $userRepo->fetchListUsers();
    // Create new user
    $user = $userRepo->createUser(
        name: 'test', 
        email: 'test@test.local', 
        password: 'test'
    );
    // Create new user with Model : MrWolfGb\Traccar\Models\User
    $user = $userRepo->createNewUser(new User([
        'name' => 'test',
        'email' => 'test@test.local',
        'password' => 'test',
    ]));
    // Update user
    $user = $userRepo->updateUser(user: $user);
    // Delete user : int|User $user
    $userRepo->deleteUser(user: $user);
    // Assign user to device : int|User $user, int|Device $device
    $userRepo->assignUserDevice(user: 1, device: 1);
    // Remove user from device : int|User $user, int|Device $device
    $userRepo->removeUserDevice(user: 1, device: 1);
}
```

- #### *Group*

Model : **[Group Model](src/Models/Group.php)**

```php
public function index(TraccarService $traccarService)
{
    $groupRepo = $traccarService->groupRepository();
    // Get list of groups
    $list = $groupRepo->fetchListGroups();
    // Create new group
    $group = $groupRepo->createGroup(name: 'test-group');
    // Create new group with Model : MrWolfGb\Traccar\Models\Group
    $group = $groupRepo->createNewGroup(group: new Group(['name' => 'test']));
    // Update group
    $user = $groupRepo->updateGroup(group: $group);
    // Delete group : int|Group $group
    $groupRepo->deleteGroup(group: $group);
}
```

- #### *Device*

Model : **[Device Model](src/Models/Device.php)**

```php
public function index(TraccarService $traccarService)
{
    $deviceRepo = $traccarService->deviceRepository();
    // Get list of devices
    $list = $deviceRepo->fetchListDevices();
    // Get user devices
    $list = $deviceRepo->getUserDevices(userId: 1);
    // Get device by id
    $device = $deviceRepo->getDeviceById(deviceId: 1);
    // Get device by uniqueId
    $device = $deviceRepo->getDeviceByUniqueId(uniqueId: 123456);
    // Create new device
    $device = $deviceRepo->createDevice(name: 'test', uniqueId: '123456789');
    // Create new device with Model : MrWolfGb\Traccar\Models\Device
    $device = $deviceRepo->createNewDevice(device: new Device([
        'name' => 'test-device',
        'uniqueId' => '123456789-d1-device',
    ]));
    // Update device
    $device = $deviceRepo->updateDevice(device: $device);
    // Delete device : int|Device $device
    $deviceRepo->deleteDevice(device: $device);
    // Update total distance and hours
    $deviceRepo->updateTotalDistanceAndHoursOfDevice(device: $device, totalDistance: 100, hours: 10);
    // Assign device to geofence : int|Device $device, int|Geofence $geofence
    $deviceRepo->assignDeviceGeofence(device: $device, geofence: $geofence);
    // Remove device from geofence : int|Device $device, int|Geofence $geofence
    $deviceRepo->removeDeviceGeofence(device: $device, geofence: $geofence);
    // Assign device to notification : int|Device $device, int|Notification $notification
    $deviceRepo->assignDeviceNotification(device: $device, notification: $notification);
    // Remove device from notification : int|Device $device, int|Notification $notification
    $deviceRepo->removeDeviceNotification(device: $device, notification: $notification);
}
```

- #### *Geofence*

Model : **[Geofence Model](src/Models/Geofence.php)**

```php
public function index(TraccarService $traccarService)
{
    $geofenceRepo = $traccarService->geofenceRepository();
    // Get list of geofences
    $list = $geofenceRepo->fetchListGeofences();
    // Get geofence
    $geofence = $geofenceRepo->createGeofence(
        name: 'test-geofence', 
        area: 'POLYGON ((34.55602185173028 -18.455295134508617, 37.67183427726626 -18.13110040602976, 34.98211925933252 -14.500119447061167, 34.55602185173028 -18.455295134508617))',
        description: 'test'
    );
    // Create new geofence with Model : MrWolfGb\Traccar\Models\Geofence
    $geofence = $geofenceRepo->createNewGeofence( geofence: new Geofence([
        'name' => 'test-geofence', 
        'area' => 'LINESTRING (38.06472440318089 -26.49821693459276, 38.4968396008517 -24.64860674974679, 37.297972401178825 -23.72380165732423, 38.099388220592346 -23.37149495544884)',
        'description' => 'test'
    ]));
    // Update geofence
    $geofence = $geofenceRepo->updateGeofence(geofence: $geofence);
    // Delete geofence : int|Geofence $geofence
    $geofenceRepo->deleteGeofence(geofence: $geofence);
}
```

- #### *Notification*

Model : **[Notification Model](src/Models/Notification.php)**

```php
public function index(TraccarService $traccarService)
{
    $notificationRepo = $traccarService->notificationRepository();
    // Get list of notifications
    $list = $notificationRepo->fetchListNotifications();
    // Create new notification
    $notification = $notificationRepo->createNotification(
        type: 'alarm', 
        notificators: ['web'], 
        always: true
    );
    // Create new notification with Model : MrWolfGb\Traccar\Models\Notification
    $notification = $notificationRepo->createNewNotification(new Notification([
        'type' => NotificationType::ALARM->value,
        'notificator' => implode(',', [
            NotificatorType::WEB->value, 
            NotificatorType::COMMAND->value
        ]),
        'always' => false,
        'commandId' => 1, // required if notificator is/contains command
    ]));
    // Update notification
    $notification = $notificationRepo->updateNotification(notification: $notification);
    // Delete notification : int|Notification $notification
    $notificationRepo->deleteNotification(notification: $notification);
    // Get notification types from Traccar server
    $list = $notificationRepo->fetchNotificationTypes();
    // Send test notification
    $notificationRepo->sendTestNotification(
        type: NotificationType::MEDIA->value, 
        notificator: NotificatorType::WEB->value
    );
}
```

- #### *Position*

Model : **[Position Model](src/Models/Position.php)**

```php
public function index(TraccarService $traccarService)
{
    $positionRepo = $traccarService->positionRepository();
    // Get list of positions
    $list = $positionRepo->fetchListPositions(
        from: now()->subHours(1), 
        to: now(), 
        id: [1, 2, 3] // optional
    );
    // Delete positions of device : int|Device $device
    $positionRepo->deletePositions(
        device: 1, 
        from: now()->subHours(1), 
        to: now()
    );
    // OsmAnd
    $positionRepo->OsmAnd(uniqueId: "1234-d1", temperature: "21.5", abc: "def");
}
```

- #### *Event*

Model : **[Event Model](src/Models/Event.php)**

```php
public function index(TraccarService $traccarService)
{
    // Get specific event details
    $event = $traccarService->eventRepository()->fetchEventInformation(eventID: 1);
}
```

- #### *Driver*

Model : **[Driver Model](src/Models/Driver.php)**

```php
public function index(TraccarService $traccarService)
{
    $driverRepo = $traccarService->driverRepository();
    // Get list of drivers
    $list = $driverRepo->fetchListDrivers();
    // Create new driver
    $driver = $driverRepo->createDriver(
        name: 'test-driver',
        uniqueId: '123456789-d1-driver'
    );
    // Create new driver with Model : MrWolfGb\Traccar\Models\Driver
    $driver = $driverRepo->createNewDriver( new Driver([
      'name' => 'test-driver',
      'uniqueId' => '123456789-d1-driver'
    ]));
    // Update driver
    $driver = $driverRepo->updateDriver(driver: $driver);
    // Delete driver : int|Driver $driver
    $driverRepo->deleteDriver(driver: $driver);
}
```

- #### *Report*

Model : **[Report Model](src/Models/Report.php)**

```php
public function index(TraccarService $traccarService)
{
    $reportRepo = $traccarService->reportRepository();
    // Get route report for specific device
    $list = $reportRepo->reportRoute(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: 1
    );
    // Get events report
    $list = $reportRepo->reportEvents(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: 1,
        type: 'engine' // optional, by default 'allEvents'
    );
    // Get summary report
    $list = $reportRepo->reportSummary(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: [1,2],
        //groupId: [1,2], // optional
        //daily: true // optional
    );
    // Get trips report
    $list = $reportRepo->reportTrips(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: 1
    );
    // Get stops report
    $list = $reportRepo->reportStops(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: 1
    );
    // Get combined report
    $list = $reportRepo->reportCombined(
        from:  now()->subHours(value: 3),
        to: now(),
        deviceId: [1,2],
        //groupId: [1,2], // optional
    );
}
```

### Commands

This command store devices in the local database using the published migration.

```bash
php artisan traccar:sync
```

Or

```bash
php artisan traccar:sync-devices
```

### Listen Traccar websocket with php

if you want to listen Traccar websocket with php you can check example in
file : [WsListenCommand](src/Commands/WsListenCommand.php)

## Traccar Custom Server

- **[Repository link](https://github.com/mr-wolf-gb/traccar-custom)**

This version is a fork of the original [TRACCAR](https://github.com/traccar/traccar) repository aimed at adding some
useful features like :

1. **_`Websocket` can be accessed from external Hosts (App):_**
2. **_`api/session/check-sid?sid=[SESSION_ID]` to check if the session is still active or not_**

## Features for this version of Traccar

#### Middleware

`TraccarSession` is a middleware that adds the session ID of traccar user to the View.

```php
// route web.php
Route::get('/', [HomeController::class, 'index'])->middleware('TraccarSession');
```

```php
// blade view
const socket = new WebSocket("{{config('traccar.websocket_url')}}?session={{$traccarSessionId}}");
socket.onerror = (error) => {
    console.log('socket error: ', error)
}
socket.onmessage = function (event) {
    var data = JSON.parse(event.data);
    console.log('socket message : ', data)
}
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mr.WOLF](https://github.com/mr-wolf-gb)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
