<?php
/**
 * User: volyanytsky
 * Date: 22.12.17
 * Time: 15:28
 */

namespace Identifiers;
use Identifiers\BaseResourceId as Id;


class StringId extends Id
{

    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}