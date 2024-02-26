# Traccar GPS server

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mr-wolf-gb/traccar.svg?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar)
[![Total Downloads](https://img.shields.io/packagist/dt/mr-wolf-gb/traccar.svg?style=flat-square)](https://packagist.org/packages/mr-wolf-gb/traccar)
![GitHub Actions](https://github.com/mr-wolf-gb/traccar/actions/workflows/main.yml/badge.svg)

This package can interact with Traccar server over API

## Table of Contents

- <a href="#installation">Installation</a>
- <a href="#features-and-usage">Features and Usage</a>
    - <a href="#multi-users-and-servers">Multi-users and servers</a>
    - <a href="#methodnotallowedhttpexception">MethodNotAllowedHttpException</a>
    - <a href="#validation-message-is-only-in-default-locale">Validation message is always in default locale</a>
- <a href="#collaborators">Collaborators</a>
- <a href="#changelog">Changelog</a>
- <a href="#license">License</a>

## :wrench: Supported Versions

Versions will be supported for a limited amount of time.

| Version | Php Version | Traccar API |
|---------|-------------|-------------|
| 1.0.0   | ^8.1        | 5.12        |

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
public function __construct(public TraccarService $traccarService)
{
    $this->traccarService->setEmail("user1@traccar.local");
    $this->traccarService->setPassword("password");
    $this->traccarService->setBaseUrl("http://localhost:8082/");
    $this->traccarService->setToken("ws://localhost:8082/api/socket");
}

```

#### Available ressources

- *Server*

Model : **[Server Model](src/Models/Server.php)**

```php
public function index(TraccarService $traccarService)
{
    $serverRepo = $this->traccarService->serverRepository();
    // Get server information
    $srv = $serverRepo->fetchServerInformation();
    // Update server information
    $serverRepo->updateServerInformation(server: $srv);
}
```

- *Session*

Model : **[Session Model](src/Models/Session.php)**

```php
public function index(TraccarService $traccarService)
{
    $sessionRepo = $this->traccarService->sessionRepository();
    // Create new session 
    $session = $sessionRepo->createNewSession();
    // Get connected session [Require user Token in configuration]
    $session = $sessionRepo->fetchSessionInformation();
    // Close session
    $sessionRepo->closeSession();
}
```

- *User*

Model : **[User Model](src/Models/User.php)**

```php
public function index(TraccarService $traccarService)
{
    $userRepo = $this->traccarService->userRepository();
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

- *Device*

Model : **[Device Model](src/Models/Device.php)**

```php
public function index(TraccarService $traccarService)
{
    $deviceRepo = $this->traccarService->deviceRepository();
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

- *Geofence*

Model : **[Geofence Model](src/Models/Geofence.php)**

```php
public function index(TraccarService $traccarService)
{
    $geofenceRepo = $this->traccarService->geofenceRepository();
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
