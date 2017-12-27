<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 12:21
 */

namespace StalkerPortal\ApiV1\Resources;

use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

abstract class BaseUser extends BaseResource
{
    public function add(AccountInterface $user)
    {
        $data = [];

        $data['stb_mac'] = $user->getMac();
        $data['login'] = $user->getLogin();
        $data['password'] = $user->getPassword();
        $data['status'] = $user->getStatus();
        $data['full_name'] = $user->getFullName();
        $data['account_number'] = $user->getAccountNumber();
        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();

        return $this->post($data);
    }

    public function updateUser(AccountInterface $user)
    {
        $data = [];

        $data['password'] = $user->getPassword();
        $data['full_name'] = $user->getFullName();
        $data['account_number'] = $user->getAccountNumber();
        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['status'] = $user->getStatus();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();

        return $this->put($user->getMac(), $data);
    }
}