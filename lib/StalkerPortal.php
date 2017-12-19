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
    
    private function setResourceFromRawPortalData(BaseResource $resource, array $data)
    {
        foreach ($data as $key => $value)
        {
            $resource->set($key, $value);
        }
        return $resource;
    }
    
    public function getStbList()
    {
        $list = $this->decodeAnswer($this->api->get("stb"));
        //return $list;
        $stbs = [];
        foreach ($list as $stbData) 
        {
            $stb = $this->setResourceFromRawPortalData(new Stb(), $stbData);
            $stbs[] = $stb;
        }
        return $stbs;
    }
}