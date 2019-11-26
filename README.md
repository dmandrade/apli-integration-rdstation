# Aplí RDStation Client

A simple package to integrate your application with [RD Station Marketing](https://rdstation.com) using oauth authentication.

## Requirements

The following versions of PHP are supported.

* PHP 7.3

## Basi Usage

Create a provide instance in yours controller constructor

```php
use Apli\Integration\RdStation\Provider\RdStation;

$this->oauthProvider = new RdStation([
    'clientId'          => Env::get('RDSTATION_CLIENT_ID'),
    'clientSecret'      => Env::get('RDSTATION_CLIENT_SECRET'),
    'redirectUri'       => url('auth/rdstation/callback'),
]);
```

In authorization route call provider authorize method

```php
$this->oauthProvider->authorize();
```

In callback route call getAccessToken:

````php
use Apli\OAuth2\Client\Auth\Tokens\AccessTokenInterface;
use Apli\OAuth2\Client\Support\Utils\GrantType;

/** @var AccessTokenInterface $accessToken */
$accessToken = $this->oauthProvider->getAccessToken(GrantType::AUTHORIZATION_CODE, [
    'code' => $request->get('code')
]);
````

### Resource Owner
To request RDStation data like contacts or updating funnels/event you need a ResourceOwner instance.
This object is a representation of rdstation authorized account.

````php
use Apli\Integration\RdStation\Provider\RdStationAccount;

$rdstationAccount = $this->oauthProvider->getResourceOwner($accessToken);
````

#### Contacts

````php
$contact = $rdstationAccount->contacts()->getByIdentifier('email@domain.com');

or you can use uuid

$contact = $rdstationAccount->contacts()->getByIdentifier('11111111-1111-1111-1111-111111111111');
````
To update a contact

````php
$contact->setJobTitle('Developer');
$contact->getAddress()->setState('SP');
$contact->getPhone()->setMobilePhone('9876543210');
$contact->getSocialNetwork()->setTwitter('teste');
$rdstationAccount->contacts()->patch($contact);
````

#### Funnels

````php
$funnel = $rdstationAccount->funnels()->getByIdentifier('email@domain.com');

// or you can use uuid

$funnel = $rdstationAccount->funnels()->getByIdentifier('11111111-1111-1111-1111-111111111111');
````

To update a funnel

````php
use Apli\Integration\RdStation\Entities\Funnel;

$funnel->setLifecycleStage(Funnel::STAGE_LEAD);
$rdstationAccount->funnels()->put('email@domain.com', $funnel);

// or you can use uuid

$rdstationAccount->funnels()->put('11111111-1111-1111-1111-111111111111', $funnel);
````

#### Events

````php
use Apli\Integration\RdStation\Entities\Event;

$event = new Event(
    Event::FAMILY_CDP,
    Event::TYPE_CONVERSION, 
    [
        'conversion_identifier' => 'conversionName',
        'email' => 'email@domain.com'
    ]
);
$rdstationAccount->events()->post($event);
````

## Persisting Access Token

You can save access token data in a database to reuse, for that we provide a toArray() method

````php
$accessTokenData = $accessToken->toArray()

// response:
// [
//     "access_token" => "xxxx",
//     "refresh_token" => "xxxxx",
//     "expires" => 1574799849,
//     "grant_type" => "Authorization",
//     "provider_name" => "rdstation"
// ]
```` 

## Restore Access Token Object

To restore access token object:

```php
use Apli\OAuth2\Client\Auth\Tokens\AccessToken;

$accessToken = new AccessToken($accessTokenData);
```
