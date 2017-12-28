<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 12:21
 */

namespace StalkerPortal\ApiV1\Resources;

use \DateTime;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

abstract class BaseUsers extends BaseStb
{
    final public function add(AccountInterface $user)
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

    final public function updateUser(AccountInterface $user)
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

    final public function switchTariffPlan($id)
    {
        return $this->put($id, ['tariff_plan' => $id]);
    }

    final public function setExpireDate($id, $date)
    {
        if(DateTime::createFromFormat("Y-m-d", $date) === false)
        {
            throw new StalkerPortalException($date .": incorrect format. STB expire date must be Y-m-d");
        }

        return $this->put($id, ['end_date' => $date]);
    }
}