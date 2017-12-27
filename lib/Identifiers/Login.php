<?php
/**
 * Users: volyanytsky
 * Date: 22.12.17
 * Time: 15:15
 */

namespace Identifiers;


class Login extends SingleUserId
{

    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}