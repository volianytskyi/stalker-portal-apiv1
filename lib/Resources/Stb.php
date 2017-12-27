<?php
/**
 * Users: volyanytsky
 * Date: 19.12.2017
 * Time: 00:14
 */

namespace StalkerPortal\ApiV1\Resources;

use StalkerPortal\ApiV1\Interfaces\Stb as StbInterface;

class Stb extends BaseStb
{
    public function getResource()
    {
        return 'stb';
    }

    public function add(StbInterface $user)
    {
        $data = [];

        $data['mac'] = $user->getMac();
        $data['login'] = $user->getLogin();
        $data['password'] = $user->getPassword();
        $data['status'] = $user->getStatus();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();
        $data['ls'] = $user->getAccountNumber();

        return $this->post($data);
    }

    public function updateStb(StbInterface $user)
    {
        $data = [];

        $data['status'] = $user->getStatus();
        $data['password'] = $user->getPassword();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();
        $data['ls'] = $user->getAccountNumber();

        return $this->put($user->getMac(), $data);
    }

    public function updateAccount(StbInterface $user)
    {
        $data = [];

        $data['status'] = $user->getStatus();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();

        return $this->put($user->getAccountNumber(), $data);
    }

}