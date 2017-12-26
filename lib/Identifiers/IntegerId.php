<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 22.12.17
 * Time: 15:28
 */

namespace Identifiers;
use Identifiers\BaseResourceId as Id;


class IntegerId extends Id
{

    /**
     * @return mixed
     */
    protected function getFilter()
    {
        return FILTER_SANITIZE_NUMBER_INT;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $number = filter_var($value, $this->getFilter());
        if($number === false || $number < 1)
        {
            throw new StalkerPortalException($value . ': incorrect value');
        }
        $this->id = $number;
    }
}