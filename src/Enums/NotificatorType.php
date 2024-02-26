<?php
/*
 * Author: WOLF
 * Name: NotificatorType.php
 * Modified : lun., 26 févr. 2024 09:41
 * Description: ...
 *
 * Copyright 2024 -[MR.WOLF]-[WS]-
 */

namespace MrWolfGb\Traccar\Enums;

enum NotificatorType: string
{
    case COMMAND = 'command';
    case MAIL = 'mail';
    //case SMS = 'sms';
    case WEB = 'web';
}
