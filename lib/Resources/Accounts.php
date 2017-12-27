<?php
/**
 * Users: volyanytsky
 * Date: 20.12.17
 * Time: 19:32
 */

namespace StalkerPortal\ApiV1\Resources;

use Identifiers\AccountNumber;
use Identifiers\BaseUserId;
use Identifiers\MacAddress;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

class Accounts extends BaseUser
{
    public function getResource()
    {
        return 'accounts';
    }

    public function updateAccount(AccountInterface $user)
    {
        $data = [];

        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['status'] = $user->getStatus();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();

        return $this->put($user->getAccountNumber(), $data);
    }

    public function deleteUser(MacAddress $mac)
    {
        return $this->delete($mac->getValue());
    }

    public function deleteAccount(AccountNumber $accountNumber)
    {
        return $this->delete($accountNumber->getValue());
    }

    public function getUser(MacAddress $mac)
    {
        return $this->get($mac->getValue());
    }

    public function getUsers(AccountNumber $accountNumber = null)
    {
        ($accountNumber === null) ? $id = '' : $id = $accountNumber->getValue();
        return $this->get($id);
    }


}