<?php
/**
 * Users: volyanytsky
 * Date: 22.12.17
 * Time: 15:15
 */

namespace Identifiers;


class MacAddress extends SingleUserId
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