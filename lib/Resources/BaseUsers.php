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
	    $data['phone'] = $user->getPhone();

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
	    $data['phone'] = $user->getPhone();

        if(!empty($user->getLogin()))
        {
          $data['stb_mac'] = $user->getMac();
          $userId = $user->getLogin();
        }

        else
        {
          $userId = $user->getMac();
        }

        return $this->put($userId, $data);
    }

    final public function switchTariffPlan($id, $tariffPlanId)
    {
        return $this->put($id, ['tariff_plan' => $tariffPlanId]);
    }

    final public function setExpireDate($id, $date)
    {
        if(DateTime::createFromFormat("Y-m-d H:i:s", $date) === false)
        {
            throw new StalkerPortalException($date .": incorrect format. User expire date must be Y-m-d H:i:s");
        }

        return $this->put($id, ['end_date' => $date]);
    }

    final public function setComment($id, $comment)
    {
        return $this->put($id, ['comment' => $comment]);
    }

    final public function setName($id, $name)
    {
        return $this->put($id, ['full_name' => $name]);
    }


    final public function setPassword($id, $password)
    {
        if(!isset($password))
        {
            throw new StalkerPortalException("password must be set");
        }

        return $this->put($id, ['password' => $password]);
    }
}
