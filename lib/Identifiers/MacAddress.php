<?php
/**
 * User: volyanytsky
 * Date: 22.12.17
 * Time: 15:15
 */

namespace Identifiers;
use Identifiers\BaseResourceId as Id;


class MacAddress extends Id
{
    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_VALIDATE_MAC;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        parent::setValue(strtoupper($value));
    }
}