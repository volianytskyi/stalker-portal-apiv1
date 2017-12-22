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
use StalkerPortal\ApiV1\Resources\User;
use StalkerPortal\ApiV1\Interfaces\User as UserInterface;
use StalkerPortal\ApiV1\Resources\IUser;

use Identifiers\BaseResourceId as ResourceId;

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

    protected function deleteIUser($id, IUser $user)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->api->delete($user->getResource(), $id));
    }

    protected function getIUser($id, IUser $user)
    {
        $this->throwIfPortalUnreachable();
        $data = $this->decodeAnswer($this->api->get($user->getResource(), $id));
        return $this->setResourceFromRawPortalData($user, $data);
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
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->getIUser($macAddress, new Stb());
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
        return $this->decodeAnswer($this->api->put("stb/".$stb->getMac(), $data));
    }

    /**
     * @param string $mac
     * @return bool
     */
    public function deleteStbByMac($mac)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->deleteIUser($macAddress, new Stb());
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

        return $this->decodeAnswer($this->api->post("stb", $data));
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
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->getIUser($macAddress, new Account());
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
        return $this->decodeAnswer($this->api->put("accounts/".$account->getAccountNumber(), $data));
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
        return $this->decodeAnswer($this->api->put("accounts/".$account->getMac(), $data));
    }

    /**accounts
     * @param string $mac
     * @return bool
     * Deletes single customer
     */
    public function deleteAccountByMac($mac)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->deleteIUser($macAddress, new Account());
    }

    /**
     * @param string $accountNumber
     * @return bool
     * Deletes the account with all customers
     */
    public function deleteAccountByNumber($accountNumber)
    {
        return $this->deleteIUser($accountNumber, new Account());
    }

    protected function addAccountOrUser(AccountInterface $account, $resource)
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

        return $this->decodeAnswer($this->api->post($resource, $data));
    }

    /**
     * @param AccountInterface $account
     * @return bool
     */
    public function addAccount(AccountInterface $account)
    {
        $this->addAccountOrUser($account, 'accounts');
    }


    /**
     * @param string $login
     * @return array
     */
    public function getUserByLogin($login)
    {
        return $this->getIUser($login, new User());
    }

    /**
     * @param string $mac
     * @return User
     */
    public function getUserByMac($mac)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->getIUser($macAddress, new User());
    }


    /**
     * @param UserInterface $user
     * @return bool
     * Updates password, full_name, account_number, tariff_plan, status, comment, e
nd_date, account_balance of a single customer
     */
    public function updateUserByMac(UserInterface $user)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['password'] = $user->getPassword();
        $data['full_name'] = $user->getFullName();
        $data['account_number'] = $user->getAccountNumber();
        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['status'] = $user->getStatus();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();
        return $this->decodeAnswer($this->api->put("users/".$user->getMac(), $data));
    }

    /**
     * @param UserInterface $user
     * @return bool
     * Updates stb_mac, password, full_name, account_number, tariff_plan, status, comment, end_date, account_balance of a single customer
     */
    public function updateUserByLogin(UserInterface $user)
    {
        $this->throwIfPortalUnreachable();
        $data = [];
        $data['stb_mac'] = $user->getMac();
        $data['password'] = $user->getPassword();
        $data['full_name'] = $user->getFullName();
        $data['account_number'] = $user->getAccountNumber();
        $data['tariff_plan'] = $user->getTariffPlanExternalId();
        $data['status'] = $user->getStatus();
        $data['comment'] = $user->getComment();
        $data['end_date'] = $user->getExpireDate();
        $data['account_balance'] = $user->getAccountBalance();
        return $this->decodeAnswer($this->api->put("users/".$user->getLogin(), $data));
    }

    /**
     * @param string $mac
     * @return bool
     * Deletes single customer
     */
    public function deleteUserByMac($mac)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->deleteIUser($macAddress, new User());
    }

    /**
     * @param string $accountNumber
     * @return bool
     * Deletes the account with all customers
     */
    public function deleteUserByLogin($login)
    {
        return $this->deleteIUser($login, new User());
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function addUser(UserInterface $user)
    {
        $this->addAccountOrUser($user, 'users');
    }

    /**
     * @param string $id
     * @param string $message
     * @param int  seconds
     * @return bool
     */
    protected function sendMessage($id, $message, $ttl)
    {
        $encodedMessage = urlencode($message);
        $seconds = $this->checkValue($ttl, FILTER_VALIDATE_INT);
        return $this->decodeAnswer($this->api->post("post/$id", ['msg' => $encodedMessage, 'ttl' => $seconds]));
    }

    /**
     * @param $accountNumber
     * @param $message
     * @param $ttl
     * @return bool
     */
    public function sendMessageByAccountNumber($accountNumber, $message, $ttl)
    {
        return $this->sendMessage($accountNumber, $message, $ttl);
    }

    /**
     * @param $mac
     * @param $message
     * @param $ttl
     * @return bool
     */
    public function sendMessageByMac($mac, $message, $ttl)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->sendMessage($macAddress, $message, $ttl);
    }


}