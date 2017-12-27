<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 11:25
 */

namespace Identifiers;


abstract class BaseIntId extends BaseResourceId
{
    protected function getFilter()
    {
        return FILTER_SANITIZE_NUMBER_INT;
    }
}