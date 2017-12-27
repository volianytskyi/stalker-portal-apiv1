<?php
/**
 * Users: volyanytsky
 * Date: 19.12.2017
 * Time: 00:14
 */

namespace StalkerPortal\ApiV1\Resources;

use Identifiers\AccountNumber;
use Identifiers\BaseUserId;
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
        $data = [];

        $data['status'] = $user->getStatus();
        $data['additional_services_on'] = $user->areAdditionalServicesOn();

        //$this->throwIfRequiredFieldsEmpty(['account_number' => $user->getAccountNumber()]);

        return $this->put($user->getAccountNumber(), $data);
    }

    public function remove(BaseUserId $id)
    {
        return $this->delete($id);
    }

    public function getStb(MacAddress $mac)
    {
        return $this->get($mac->getValue());
    }

    public function getStbs(AccountNumber $accountNumber = null)
    {
        ($accountNumber === null) ? $id = '' : $id = $accountNumber->getValue();
        return $this->get($id);
    }


}