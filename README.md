# Unofficial [Stalker Portal REST API v1](https://wiki.infomir.eu/eng/ministra-tv-platform/ministra-setup-guide/rest-api-v1) SDK

## Examples
```php
require_once '../vendor/autoload.php';

use Http\HttpClient as Http; //https://github.com/volyanytsky/http

//https://wiki.infomir.eu/rus/ministra-tv-platform/rukovodstvo-po-nastrojke-ministra/rest-api-v1#RESTAPIv1-ACCOUNTS1
use StalkerPortal\ApiV1\Resources\Accounts; 

use StalkerPortal\ApiV1\Interfaces\Account;

//https://wiki.infomir.eu/rus/ministra-tv-platform/rukovodstvo-po-nastrojke-ministra/rest-api-v1#RESTAPIv1-SEND_EVENT
use StalkerPortal\ApiV1\Resources\SendEvent; 

//https://wiki.infomir.eu/eng/ministra-tv-platform/ministra-setup-guide/rest-api-v1#RESTAPIv1-TARIFFS
use StalkerPortal\ApiV1\Resources\Tariffs; 

//need to implement Account interface to use Accounts::add() and Accounts::update()
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

//values from the Stalker Portal configuration file https://wiki.infomir.eu/eng/ministra-tv-platform/ministra-installation-guide/configuration-file
$url = 'http://10.118.41.221/stalker_portal/api'; //stalker_api_url
$user = 'stalker'; //api_auth_login
$pass = 'secret_pass'; //api_auth_password

$http = new Http($url, $user, $pass);
$event = new SendEvent($http);
$res = $event->sendMessage('00:1a:79:34:c3:db', 'Hello World', 300, true);
print_r($res); 
//prints '1' if message was delivered; the box will be reloaded after printing the message on the TV screen

$tariffs = new Tariffs($http);
$allTariffs = $tariffs->select();

$portal = new Accounts($http);
$users = $portal->select(null);
//the array with all users data
    
$mac = '00:1a:79:31:db:ef';
$acc = '111111';
$login = 'johndoe';
$date = '2020-01-01';
$newDate = '2020-12-31';

$user = new User();
$user->mac = $mac;
$user->login = $login;
$user->accountNumber = $acc;
$user->expDate = $date;
$user->name = 'John Doe';
$user->comment = 'test comment changed';
$user->balance = '5000';
$user->accountNumber = '222222';
$user->tariff = '05'; //external_id of needed tariff plan from $allTariffs selection
$add = $portal->add($user); //$add == 1 if user has been added successfully

$portal->switchStatus($user->mac, true); 
//returns 1 if status has been switched on

$portal->setExpireDate($user->accountNumber, $newDate); 
//sets $newdate as expire billing date to all users under that account number

$users = $portal->select('00:1a:79:31:db:ef', '00:1a:79:34:b3:eb');
//return array with 2 users data
```

## License
This project is released under the [WTFPL](https://en.wikipedia.org/wiki/WTFPL) License.