<?php
/**
 * User: volyanytsky
 * Date: 20.12.17
 * Time: 19:31
 */

namespace StalkerPortal\ApiV1\Interfaces;

interface Account
{
    public function getLogin();
    public function getPassword();
    public function getFullName();
    public function getAccountNumber();
    public function getTariffPlanExternalId();
    public function getStatus();
    public function getMac();
    public function getComment();
    public function getExpireDate();
    public function getAccountBalance();
}