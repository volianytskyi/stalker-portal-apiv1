<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 27.12.17
 * Time: 16:37
 */

namespace StalkerPortal\ApiV1\Resources;

use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;

abstract class BaseStb extends BaseResource
{
    final public function remove($id)
    {
        return $this->delete($id);
    }

    final public function select()
    {
        $identifiers = implode(',', func_get_args());
        return $this->get($identifiers);
    }

    final public function switchStatus($id, $status)
    {
        return $this->put($id, ['status' => (boolean)$status]);
    }

    final public function isMacUnique($mac)
    {
        try {
            $result = $this->get($mac);
        } catch (StalkerPortalException $e) {
            if($e->getMessage() === 'Account not found')
            {
                return true;
            }
            throw new StalkerPortalException($e->getMessage());
        }

        if($result === false)
        {
            return true;
        }

        return false;
    }
}
