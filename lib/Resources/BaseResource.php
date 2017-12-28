<?php
/**
 * Users: volyanytsky
 * Date: 19.12.17
 * Time: 17:52
 */

namespace StalkerPortal\ApiV1\Resources;

use Http\HttpClient as Http;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;

abstract class BaseResource
{
    protected $http;

    protected $resource;
    abstract public function getResource();

    final public function __construct(Http $http)
    {
        $this->resource = $this->getResource();
        $this->http = $http;
    }

    final protected function decodeAnswer($jsonString)
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

    final public function checkConnection()
    {
        try
        {
            $data = $this->http->get("");
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

    final protected function throwIfPortalUnreachable()
    {
        if($this->checkConnection() === false)
        {
            throw new StalkerPortalException($this->http->getUrl() . " is unreachable");
        }
    }

    protected function post(array $data)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($this->getResource(), $data));
    }

    final protected function put($id, array $data)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->put($this->getResource()."/$id", $data));
    }

    final protected function delete($id)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->delete($this->getResource(), $id));
    }

    final protected function get($id = '')
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->get($this->getResource(), $id));
    }

    final protected function throwIfRequiredFieldsEmpty(array $data)
    {
        foreach ($data as $key => $value)
        {
            if(empty($value))
            {
                throw new StalkerPortalException($this->resource.":".$key . " is empty");
            }
        }
    }
}