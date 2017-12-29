<?php
/**
 * Created by PhpStorm.
 * User: mousemaster
 * Date: 28.12.17
 * Time: 14:00
 */

require_once '../vendor/autoload.php';



use Http\HttpClient as Http;
use StalkerPortal\ApiV1\Resources\Accounts;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use StalkerPortal\ApiV1\Interfaces\Account;
use StalkerPortal\ApiV1\Resources\Users;
use StalkerPortal\ApiV1\Resources\Tariffs;
use StalkerPortal\ApiV1\Resources\SendEvent;
use StalkerPortal\ApiV1\Resources\StbMsg;
use StalkerPortal\ApiV1\Resources\Itv;

class User implements Account
{
    public $mac;
    public $login;
    public $password;
    public $accountNumber;
    public $status;
    public $tariff;
    public $comment;
    public $expDate;
    public $balance;
    public $name;

    public function getMac()
    {
        return $this->mac;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTariffPlanExternalId()
    {
        return $this->tariff;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getExpireDate()
    {
        return $this->expDate;
    }

    public function getAccountBalance()
    {
        return $this->balance;
    }

    public function getFullName()
    {
        return $this->name;
    }
}


$url = 'http://10.118.41.221/stalker_portal/api';
$user = 'stalker';
$pass = 'secret_pass';

try {
    $http = new Http($url, $user, $pass);

//    $event = new SendEvent($http);
//
//    $res = $event->sendMessage('00:1a:79:34:c3:db', 'хуй хуй хуй перезагрузка', 300, true);

    $itv = new Itv($http);

    $res = $itv->select();

    print_r($res); echo "\n"; exit;





    $portal = new Accounts($http);
    //$portal = new Users($http);

    //$users = $portal->select(null);
    $tariffs = new Tariffs($http);
    //print_r($tariffs->select()); echo "\n\n"; exit;


    $mac = '00:1a:79:31:db:ef';
    $acc = '111111';
    $login = 'fdsvdsfvvedvervverv';
    $date = '2020-01-01';
    $newDate = '2020-12-31';

    $user = new User();
    $user->mac = $mac;
    $user->login = $login;
    $user->accountNumber = $acc;
    $user->expDate = $date;
    $user->name = 'test name changed';
    $user->comment = 'test comment changed';
    $user->balance = '5000';
    $user->accountNumber = '222222';
    $user->tariff = '05';

    $res = $portal->select('00:1a:79:31:db:ef', '00:1a:79:34:b3:eb');

    print_r($res); echo "\n\n";
}
catch (StalkerPortalException $e)
{
    echo $e->getMessage() . "\n";
}
catch (\Exception $e)
{
    echo $e->getMessage() . "\n";
}
