<?php
/**
 * User: volyanytsky
 * Date: 19.12.17
 * Time: 17:52
 */

namespace StalkerPortal\ApiV1\Resources;
use Http\HttpClient as Http;

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
}