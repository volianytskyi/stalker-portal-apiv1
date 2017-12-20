<?php

/**
 * User: volyanytsky
 * Date: 18.12.2017
 * Time: 23:34
 */

namespace StalkerPortal\ApiV1;

use StalkerPortal\ApiV1\Resources\BaseResource;
use StalkerPortal\ApiV1\Rest\Rest;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use StalkerPortal\ApiV1\Resources\Stb;
use StalkerPortal\ApiV1\Interfaces\Stb as StbInterface;
use StalkerPortal\ApiV1\Resources\Account;
use StalkerPortal\ApiV1\Interfaces\Account as AccountInterface;

class StalkerPortal
{
    protected $api;
    public function __construct(Rest $api)
    {
        $this->api = $api;
    }


    /**
     * @param string $jsonString
     * @return array|null
     * @throws StalkerPortalException
     */
    protected function decodeAnswer($jsonString)
    {
        $answer = json_decode($jsonString, true);

        if($answer['status'] === 'OK')
        {
            return $answer['results'];
        }

        if($answer['status'] === 'ERROR')
        {
            throw new StalkerPortalException($answer['error']);
        }

        return null;
    }

    /**
     * @return bool
     * @throws StalkerPortalException
     */
    public function checkConnection()
    {
        try
        {
            $data = $this->api->get("");
            $res = $this->decodeAnswer($data);
        }
        catch(StalkerPortalException $e)
        {
            if($e->getMessage() === 'Empty resource')
            {
                return true;
            }
            throw new StalkerPortalException($e->getMessage());
        }

        return false;
    }

    protected function throwIfPortalUnreachable()
    {
        if($this->checkConnection() === false)
        {
            throw new StalkerPortalException($this->api->getUrl() . " is unreachable");
        }
    }

    /**
     * @param BaseResource $resource
     * @param array $data
     * @return BaseResource
     */
    protected function setResourceFromRawPortalData(BaseResource $resource, array $data)
    {
        foreach ($data as $key => $value)
        {
            if($resource !== null)
            {
                $resource->set($key, $value);
            }
        }
        return $resource;
    }

    protected function checkValue($value, $filter)
    {
        $checkedValue = filter_var($value, $filter);
        if($checkedValue === false)
        {
            throw new StalkerPortalException($value . ': incorrect value');
        }
        return $checkedValue;
    }
    
    public function getAllStb()
    {
        $this->throwIfPortalUnreachable();
        $list = $this->decodeAnswer($this->api->get("stb"));
        $stbs = [];
        foreach ($list as $stbData) 
        {
            $stb = $this->setResourceFromRawPortalData(new Stb(), $stbData);
            $stbs[] = $stb;
        }
        return $stbs;
    }

    /**
     * @param string $ls
     * @return array
     */
    public function getStbByPersonalAccount($ls)
    {
        $this->throwIfPortalUnreachable();
        $list = $this->decodeAnswer($this->api->get("stb", $ls));
        $stbs = [];
        foreach ($list as $stbData)
        {
            $stb = $this->setResourceFromRawPortalData(new Stb(), $stbData);
            $stbs[] = $stb;
        }
        return $stbs;
    }

    /**
     * @param string $mac
     * @return Stb
     */
    public function getStbByMac($mac)
    {
        $this->throwIfPortalUnreachable();
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        $stbData = $this->decodeAnswer($this->api->get("stb", $macAddress));
        return $this->setResourceFromRawPortalData(new Stb(), $stbData);
    }


    /**
     * @param StbInterface $stb
     * @return bool
     */
    public function updateStb(StbInterface $stb)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['status'] = $stb->getStatus();
        $data['password'] = $stb->getPassword();
        $data['additional_services_on'] = $stb->areAdditionalServicesOn();
        $data['ls'] = $stb->getPersonalAccount();
        return $this->api->put("stb/".$stb->getMac(), $data);
    }

    /**
     * @param string $mac
     * @return bool
     */
    public function deleteStbByMac($mac)
    {
        $this->throwIfPortalUnreachable();
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->api->delete("stb", $macAddress);
    }

    /**
     * @param StbInterface $stb
     * @return bool
     */
    public function addStb(StbInterface $stb)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['mac'] = $stb->getMac();
        $data['login'] = $stb->getLogin();
        $data['password'] = $stb->getPassword();
        $data['status'] = $stb->getStatus();
        $data['additional_services_on'] = $stb->areAdditionalServicesOn();
        $data['ls'] = $stb->getPersonalAccount();

        return $this->api->post("stb", $data);
    }


    /**
     * @return array
     */
    public function getAllAccounts()
    {
        $this->throwIfPortalUnreachable();
        $allData = $this->decodeAnswer($this->api->get("accounts"));
        $accounts = [];
        foreach ($allData as $accData)
        {
            $account = $this->setResourceFromRawPortalData(new Account(), $accData);
            $accounts[] = $account;
        }
        return $accounts;
    }

    /**
     * @param string $ls
     * @return array
     */
    public function getAccountByNumber($accountNumber)
    {
        $this->throwIfPortalUnreachable();
        $allData = $this->decodeAnswer($this->api->get("accounts", $accountNumber));
        $accounts = [];
        foreach ($allData as $accData)
        {
            $account = $this->setResourceFromRawPortalData(new Account(), $accData);
            $accounts[] = $account;
        }
        return $accounts;
    }

    /**
     * @param string $mac
     * @return Account
     * @throws StalkerPortalException
     */
    public function getAccountByMac($mac)
    {
        $this->throwIfPortalUnreachable();
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        $accData = $this->decodeAnswer($this->api->get("accounts", $macAddress));
        return $this->setResourceFromRawPortalData(new Account(), $accData);
    }


    /**
     * @param AccountInterface $account
     * @return bool
     * Updates tariff_plan, status, comment, end_date, account_balance of all users in the account
     */
    public function updateAccountByNumber(AccountInterface $account)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['tariff_plan'] = $account->getTariffPlanExternalId();
        $data['status'] = $account->getStatus();
        $data['comment'] = $account->getComment();
        $data['end_date'] = $account->getExpireDate();
        $data['account_balance'] = $account->getAccountBalance();
        return $this->api->put("accounts/".$account->getAccountNumber(), $data);
    }

    /**
     * @param AccountInterface $account
     * @return bool
     * Updates password, full_name, account_number, tariff_plan, status, comment, end_date, account_balance of a single customer
     */
    public function updateAccountByMac(AccountInterface $account)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['password'] = $account->getPassword();
        $data['full_name'] = $account->getFullName();
        $data['account_number'] = $account->getAccountNumber();
        $data['tariff_plan'] = $account->getTariffPlanExternalId();
        $data['status'] = $account->getStatus();
        $data['comment'] = $account->getComment();
        $data['end_date'] = $account->getExpireDate();
        $data['account_balance'] = $account->getAccountBalance();
        return $this->api->put("accounts/".$account->getMac(), $data);
    }

    /**
     * @param string $mac
     * @return bool
     * Deletes single customer
     */
    public function deleteAccountByMac($mac)
    {
        $this->throwIfPortalUnreachable();
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->api->delete("accounts", $macAddress);
    }

    /**
     * @param string $accountNumber
     * @return bool
     * Deletes the account with all customers
     */
    public function deleteAccountByNumber($accountNumber)
    {
        $this->throwIfPortalUnreachable();
        return $this->api->delete("accounts", $accountNumber);
    }

    /**
     * @param AccountInterface $account
     * @return bool
     */
    public function addAccount(AccountInterface $account)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['login'] = $account->getLogin();
        $data['password'] = $account->getPassword();
        $data['full_name'] = $account->getFullName();
        $data['account_number'] = $account->getAccountNumber();
        $data['tariff_plan'] = $account->getTariffPlanExternalId();
        $data['status'] = $account->getStatus();
        $data['stb_mac'] = $account->getMac();
        $data['comment'] = $account->getComment();
        $data['end_date'] = $account->getExpireDate();
        $data['account_balance'] = $account->getAccountBalance();

        return $this->api->post("accounts", $data);
    }


}