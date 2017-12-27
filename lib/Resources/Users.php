<?php
/**
 * Users: volyanytsky
 * Date: 20.12.17
 * Time: 19:32
 */

namespace StalkerPortal\ApiV1\Resources;


class Users extends BaseUsers
{
    public function getResource()
    {
        return 'users';
    }

    public function isLoginUnique($login)
    {
        return $this->isMacUnique($login);
    }
}