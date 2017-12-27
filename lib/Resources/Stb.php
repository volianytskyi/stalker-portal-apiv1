<?php
/**
 * Users: volyanytsky
 * Date: 19.12.2017
 * Time: 00:14
 */

namespace StalkerPortal\ApiV1\Resources;

use Identifiers\AccountNumber;
use Identifiers\IStb;
use Identifiers\MacAddress;
use StalkerPortal\ApiV1\Interfaces\Stb as StbInterface;

class Stb extends BaseResource
{
    public function getResource()
    {
        return 'stb';
    }

    public function add(StbInterface $user)
    {
        if($user === null)
        {
            return false;
        }

        $data = [];

        $data['mac'] = $user->getMac();
        $data['login'] = $user->getLogin();
        $data['password'] = $user->getPassword();
        $data['status'] = $user->getStatus();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();
        $data['ls'] = $user->getAccountNumber();

        //$this->throwIfRequiredFieldsEmpty(['login' => $data['login']]);

        return $this->post($data);
    }

    public function updateByMac(StbInterface $user)
    {
        if($user === null)
        {
            return false;
        }

        $data = [];

        $data['status'] = $user->getStatus();
        $data['password'] = $user->getPassword();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();
        $data['ls'] = $user->getAccountNumber();

        //$this->throwIfRequiredFieldsEmpty(['mac' => $user->getMac()]);

        return $this->put($user->getMac(), $data);
    }

    public function updateByAccountNumber(StbInterface $user)
    {
        if($user === null)
        {
            return false;
        }

        $data = [];

        $data['status'] = $user->getStatus();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();

        //$this->throwIfRequiredFieldsEmpty(['account_number' => $user->getAccountNumber()]);

        return $this->put($user->getAccountNumber(), $data);
    }

    public function remove(IStb $id)
    {
        if($id === null)
        {
            return false;
        }

        return $this->delete($id->getValue());
    }

    public function getStb(IStb $id)
    {
        if($id === null)
        {
            return null;
        }

        return $this->get($id);
    }

    public function getAll()
    {
        return $this->get('');
    }

}