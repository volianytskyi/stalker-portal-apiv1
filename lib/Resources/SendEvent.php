<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 17:48
 */

namespace StalkerPortal\ApiV1\Resources;


class SendEvent extends BaseResource
{

    public function getResource()
    {
        return 'send_event';
    }

    public function sendMessage($id, $message, $ttl, $reboot = false)
    {
        $data = [
            'event' => 'send_msg',
            'msg' => $message,
            'ttl' => (int)$ttl,
            'need_reboot' => $reboot
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function sendMessageToAll($message, $ttl, $reboot = false)
    {
        return $this->sendMessage('', $message, $ttl, $reboot);
    }

    public function reboot($id = '')
    {
        $data = [
            'event' => 'reboot'
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function reloadPortal($id = '')
    {
        $data = [
            'event' => 'reload_portal'
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function playChannel($channelNumber, $id = '')
    {
        $data = [
            'event' => 'play_channel',
            'channel' => (int)$channelNumber
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function updateChannels($id = '')
    {
        $data = [
            'event' => 'update_channels'
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function updateImage($id = '')
    {
        $data = [
            'event' => 'update_image'
        ];

        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource()."/$id", $data));
    }

    public function cutOff($id)
    {
        $data = [
            'event' => 'cut_off'
        ];

        $this->throwIfPortalUnreachable();
        $this->http->post($this->getResource()."/$id", $data);

        //the status must be switched off in addition https://wiki.infomir.eu/rus/ministra-tv-platform/rukovodstvo-po-nastrojke-ministra/rest-api-v1#RESTAPIv1-SEND_EVENT
        $accounts = new Accounts($this->http);
        return $accounts->switchStatus($id, 0);
    }


}