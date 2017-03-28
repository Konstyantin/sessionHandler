<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.03.2017
 * Time: 10:23
 */

namespace App\Session;

/**
 * Class SessionEventMessage
 *
 * SessionEventMessage component which store message signalization session event
 * @package App\Session
 */
final class SessionEventMessage
{
    const SESSION_START         = 'Session start';
    const SESSION_STOP          = 'Session stop';
    const SESSION_UNSET         = 'Session unset';
    const SESSION_LIST          = 'Get session list data';
    const SESSION_LIFE          = 'Get session lifetime';
    const SESSION_STATUS        = 'Get session status';
    const SESSION_SET           = 'Set session ';
    const SESSION_GET           = 'Get session ';
    const SESSION_CHECK_EXIST   = 'Check exists session ';
    const SESSION_UNSET_KEY     = 'Session unset key ';
    const SESSION_SAVE_PATH     = 'Session set save path';
}