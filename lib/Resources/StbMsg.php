<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 12:33
 */

namespace StalkerPortal\ApiV1\Resources;


class StbMsg extends BaseResource
{

    public function getResource()
    {
        return 'stb_msg';
    }

    public function sendMessage($id, $message, $ttl)
    {
        $data = [
            'msg' => $message,
            'ttl' => (int)$ttl
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }
}