<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 28.12.17
 * Time: 11:44
 */

namespace StalkerPortal\ApiV1\Resources;


class Tariffs extends BaseResource
{

    public function getResource()
    {
        return 'tariffs';
    }

    public function select($id = '')
    {
        return $this->get($id);
    }
}