<?php
/**
 * Created by PhpStorm.
 * Users: mousemaster
 * Date: 26.12.17
 * Time: 19:34
 */

namespace Identifiers;


class AccountNumber extends BaseResourceId implements IStb, IUsers, IAccounts, IStbMsg, ISendEvent, IStbModules, IItvSubscription, IAccountSubscription
{

    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}