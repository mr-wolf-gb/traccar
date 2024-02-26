<?php
/*
 * Author: WOLF
 * Name: NotificationType.php
 * Modified : lun., 26 févr. 2024 09:30
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */


namespace MrWolfGb\Traccar\Enums;
enum NotificationType: string
{
    case  COMMAND_RESULT = 'commandResult';
    case  DEVICE_ONLINE = 'deviceOnline';
    case  DEVICE_UNKNOWN = 'deviceUnknown';
    case  DEVICE_OFFLINE = 'deviceOffline';
    case  DEVICE_INACTIVE = 'deviceInactive';
    case  QUEUED_COMMAND_SENT = 'queuedCommandSent';
    case  DEVICE_MOVING = 'deviceMoving';
    case  DEVICE_STOPPED = 'deviceStopped';
    case  DEVICE_OVERSPEED = 'deviceOverspeed';
    case  DEVICE_FUEL_DROP = 'deviceFuelDrop';
    case  DEVICE_FUEL_INCREASE = 'deviceFuelIncrease';
    case  GEOFENCE_ENTER = 'geofenceEnter';
    case  GEOFENCE_EXIT = 'geofenceExit';
    case  ALARM = 'alarm';
    case  IGNITION_ON = 'ignitionOn';
    case  IGNITION_OFF = 'ignitionOff';
    case  MAINTENANCE = 'maintenance';
    case  TEXT_MESSAGE = 'textMessage';
    case  DRIVER_CHANGED = 'driverChanged';
    case  MEDIA = 'media';
}
