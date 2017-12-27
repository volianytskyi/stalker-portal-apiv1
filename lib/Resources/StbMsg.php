<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 12:33
 */

namespace StalkerPortal\ApiV1\Resources;


use Identifiers\AccountNumber;
use Identifiers\MacAddress as Mac;

class StbMsg extends BaseResource
{

    public function getResource()
    {
        return 'stb_msg';
    }

    private function sendMessage($id, $message, $ttl)
    {
        $data = [
            'msg' => urlencode($message),
            'ttl' => (int)$ttl
        ];

        return $this->post($id, $data);
    }

    public function sendMessageToUser(Mac $mac, $message, $ttl)
    {
        return $this->sendMessage($mac->getValue(), $message, $ttl);
    }

    public function sendMessageToAccount(AccountNumber $accountNumber, $message, $ttl)
    {
        return $this->sendMessage($accountNumber->getValue(), $message, $ttl);
    }

}