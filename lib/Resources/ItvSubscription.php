<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 28.12.17
 * Time: 10:59
 */

namespace StalkerPortal\ApiV1\Resources;


class ItvSubscription extends BaseResource
{

    public function getResource()
    {
        return 'itv_subscription';
    }

    public function select($id = '')
    {
        return $this->get($id);
    }

    public function update(array $channels, $id = '')
    {
        $data = [
            'additional_services' => true
        ];

        foreach ($channels as $id)
        {
            $data['sub_ch'][] = $id;
        }

        return $this->put($id, $data);
    }
}