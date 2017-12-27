<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 11:31
 */

namespace Identifiers;


class ChannelId extends BaseResourceId implements IItv
{
    protected function getFilter()
    {
        return FILTER_SANITIZE_NUMBER_INT;
    }
}