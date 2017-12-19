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

    /**
     * @param BaseResource $resource
     * @param array $data
     * @return BaseResource
     */
    private function setResourceFromRawPortalData(BaseResource $resource, array $data)
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

    private function checkValue($value, $filter)
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
     * @throws StalkerPortalException
     */
    public function getStbByMac($mac)
    {
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        $stbData = $this->decodeAnswer($this->api->get("stb", $macAddress));
        return $this->setResourceFromRawPortalData(new Stb(), $stbData);
    }

    /**
     * @param StbInterface $stb
     * @return null|Stb
     */
    public function getStb(StbInterface $stb)
    {
        if(!empty($stb->getMac()))
        {
            return $this->getStbByMac($stb->getMac());
        }
        return null;
    }

    public function updateStb(StbInterface $stb)
    {
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
        $macAddress = $this->checkValue($mac, FILTER_VALIDATE_MAC);
        return $this->api->delete("stb", $macAddress);
    }

    /**
     * @param StbInterface $stb
     * @return bool
     */
    public function deleteStb(StbInterface $stb)
    {
        return $this->deleteStbByMac($stb->getMac());
    }

    public function addStb(StbInterface $stb)
    {
        $data = [];
        $data['mac'] = $stb->getMac();
        $data['login'] = $stb->getLogin();
        $data['password'] = $stb->getPassword();
        $data['status'] = $stb->getStatus();
        $data['additional_services_on'] = $stb->areAdditionalServicesOn();
        $data['ls'] = $stb->getPersonalAccount();

        return $this->api->post("stb", $data);
    }


}