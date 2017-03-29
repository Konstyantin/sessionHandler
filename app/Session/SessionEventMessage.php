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
    const SESSION_WRITE         = 'Write to session ';
    const SESSION_READ          = 'Read form session ';
    const SESSION_UNSET_KEY     = 'Session unset key ';
    const SESSION_DESTROY       = 'Session destroy';
    const SESSION_CLOSE         = 'Session close';
    const SESSION_CLEANUP       = 'Cleanup old sessions';
}