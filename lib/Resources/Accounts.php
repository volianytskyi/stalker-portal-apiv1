<?php
/**
 * User: volyanytsky
 * Date: 20.12.17
 * Time: 19:32
 */

namespace StalkerPortal\ApiV1\Resources;

use Identifiers\AccountNumber;
use Identifiers\BaseUserId;
use Identifiers\MacAddress;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

class Accounts extends BaseResource
{
    public function getResource()
    {
        return 'accounts';
    }

    public function add(AccountInterface $user)
    {
        if(empty($user->getLogin()))
        {
            throw new StalkerPortalException($this->resource . ": login is required");
        }

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

    public function updateOne(AccountInterface $user)
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

    public function updateMultiple(AccountInterface $user)
    {
        $data = [];

        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['status'] = $user->getStatus();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();

        return $this->put($user->getAccountNumber(), $data);
    }

    public function remove(BaseUserId $id)
    {
        return $this->delete($id);
    }

    public function getOne(MacAddress $mac)
    {
        return $this->get($mac->getValue());
    }

    public function getMultiple(AccountNumber $accountNumber = null)
    {
        ($accountNumber === null) ? $id = '' : $id = $accountNumber->getValue();
        return $this->get($id);
    }


}