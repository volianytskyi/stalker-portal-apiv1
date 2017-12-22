<?php
/**
 * User: volyanytsky
 * Date: 22.12.17
 * Time: 15:07
 */

namespace Identifiers;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;

abstract class BaseResourceId
{
    protected $id;

    /**
     * @param $value
     * @param $filter
     * @return mixed
     * @throws StalkerPortalException
     */
    public function setValue($value)
    {
        $checkedValue = filter_var($value, $this->getFilter());
        if($checkedValue === false)
        {
            throw new StalkerPortalException($value . ': incorrect value');
        }
        $this->id = $checkedValue;
    }

    /**
     * @return mixed
     */
    abstract protected function getFilter();

    /**
     * BaseResourceId constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $this->setValue($id);
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->id;
    }

}