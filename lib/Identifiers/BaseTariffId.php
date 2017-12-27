<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 11:27
 */

namespace Identifiers;


abstract class BaseTariffId extends BaseResourceId
{
    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}