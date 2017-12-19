<?php
/**
 * Created by PhpStorm.
 * User: serge
 * Date: 19.12.2017
 * Time: 00:14
 */

namespace StalkerPortal\ApiV1\Resources;

use StalkerPortal\ApiV1\Resources\BaseResource;

class Stb extends BaseResource
{
    public function getFields()
    {
        return ['mac', 'ls', 'login', 'online', 'ip', 'version', 'additional_services_on', 'last_active'];
    }

    protected $filters = [
        'mac' => FILTER_VALIDATE_MAC,
        'online' => FILTER_VALIDATE_BOOLEAN,
        'ip' => FILTER_VALIDATE_IP
    ];

}