<?php
/**
 * Users: volyanytsky
 * Date: 20.12.17
 * Time: 19:32
 */

namespace StalkerPortal\ApiV1\Resources;

use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

class Accounts extends BaseUsers
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

}