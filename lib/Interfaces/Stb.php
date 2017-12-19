<?php
/**
 * User: volyanytsky
 * Date: 19.12.2017
 * Time: 00:10
 */

namespace StalkerPortal\ApiV1\Interfaces;


interface Stb
{
    public function getMac();
    public function getPersonalAccount();
    public function getLogin();
    public function getStatus();
    public function isOnline();
    public function getIp();
    public function getVersion();
    public function areAdditionalServicesOn();
    public function getLastActivityTime();
}