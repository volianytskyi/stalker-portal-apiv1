<?php
/**
 * Users: volyanytsky
 * Date: 20.12.17
 * Time: 19:32
 */

namespace StalkerPortal\ApiV1\Resources;
use Identifiers\SingleUserId;


class Users extends BaseResource
{
    public function getResource()
    {
        return 'users';
    }

    public function deleteUser(SingleUserId $id)
    {
        return $this->delete($id->getValue());
    }

    public function getUser(SingleUserId $id)
    {
        return $this->get($id->getValue());
    }

    public function getUsers()
    {
        return $this->get('');
    }
}