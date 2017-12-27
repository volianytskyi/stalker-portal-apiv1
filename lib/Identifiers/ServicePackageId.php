<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 11:35
 */

namespace Identifiers;


class ServicePackageId extends BaseResourceId implements IServicesPackage
{
    protected function getFilter()
    {
        return FILTER_SANITIZE_STRING;
    }
}