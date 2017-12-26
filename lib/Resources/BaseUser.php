<?php
/**
 * User: volyanytsky
 * Date: 26.12.17
 * Time: 19:11
 */

namespace StalkerPortal\ApiV1\Resources;

use Identifiers\BaseUserId as Id;
use Identifiers\AccountNumber;
use Identifiers\MacAddress as Mac;

use StalkerPortal\ApiV1\Interfaces\User;


abstract class BaseUser extends BaseResource
{
    final public function deleteUser(Id $id)
    {
        return $this->delete($this->resource, $id->getValue());
    }

    final public function getUser(Mac $mac)
    {
        return $this->get($this->resource, $mac->getValue());
    }

    final public function getUsers(AccountNumber $accountNumber = null)
    {
        $id = '';
        if($accountNumber !== null)
        {
            $id = $accountNumber->getValue();
        }

        return $this->get($this->resource, $id);
    }

    abstract public function addUser(User $user);

    abstract public function updateUser(User $user);

}