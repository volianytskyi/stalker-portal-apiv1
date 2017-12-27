<?php
/**
 * Users: volyanytsky
 * Date: 20.12.17
 * Time: 19:31
 */

namespace StalkerPortal\ApiV1\Interfaces;

interface Account
{
    public function getMac();
    public function getLogin();
    public function getPassword();
    public function getAccountNumber();
    public function getStatus();
    public function getTariffPlanExternalId();
    public function getComment();
    public function getExpireDate();
    public function getAccountBalance();
    public function getFullName();
}