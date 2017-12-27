<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 11:33
 */

namespace Identifiers;


class TariffPlanId extends BaseResourceId implements ITariffs
{
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}