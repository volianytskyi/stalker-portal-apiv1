<?php
/**
 * User: volyanytsky
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

    final protected function post($resource, array $data)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->post($resource, $data));
    }

    final protected function put($resource, $data)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->put($resource, $data));
    }

    final protected function delete($resource, $id)
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->delete($resource, $id));
    }

    final protected function get($resource, $id = '')
    {
        $this->throwIfPortalUnreachable();
        return $this->decodeAnswer($this->http->det($resource, $id));
    }
}