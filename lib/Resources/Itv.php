<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 28.12.17
 * Time: 10:51
 */

namespace StalkerPortal\ApiV1\Resources;


class Itv extends BaseResource
{

    public function getResource()
    {
        return 'itv';
    }

    public function select($id = '')
    {
        return $this->get($id);
    }
}