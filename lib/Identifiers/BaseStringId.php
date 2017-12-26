<?php
/**
 * User: volyanytsky
 * Date: 22.12.17
 * Time: 15:28
 */

namespace Identifiers;

abstract class BaseStringId extends BaseResourceId
{

    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}