<?php
/**
 * User: volyanytsky
 * Date: 18.12.2017
 * Time: 23:06
 */

namespace Rest;


interface RestInterface
{
    /**
     * @param $resource
     * @param string $id
     * @return mixed
     */
    public function get($resource, $id = '');

    /**
     * @param $resource
     * @param array $data
     * @return mixed
     */
    public function post($resource, array $data);

    /**
     * @param $resource
     * @param array $data
     * @return mixed
     */
    public function put($resource, array $data);

    /**
     * @param $resource
     * @param $id
     * @return mixed
     */
    public function delete($resource, $id);
}