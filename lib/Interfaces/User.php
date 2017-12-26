<?php
/**
 * User: volyanytsky
 * Date: 26.12.17
 * Time: 18:09
 */

namespace StalkerPortal\ApiV1\Interfaces;


interface User
{
    public function getMac();
    public function getLogin();
    public function getPassword();
    public function getAccountNumber();
    public function getStatus();
}