<?php

namespace Apli\Integration\RdStation\Provider;

use Apli\Integration\RdStation\Concerns\WithCheckResponseTrait;
use Apli\Integration\RdStation\Services\ContactService;
use Apli\Integration\RdStation\Services\EventService;
use Apli\Integration\RdStation\Services\FunnelService;
use Apli\OAuth2\Client\Auth\Concerns\WithBearerAuthorizationTrait;
use Apli\OAuth2\Client\Auth\Tokens\AccessTokenInterface;
use Apli\OAuth2\Client\Provider\AbstractResourceOwner;
use Apli\OAuth2\Client\Support\DataStructures\Arr;
use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;
use Apli\OAuth2\Client\Support\Http\Concerns\WithRequestFactoryTrait;
use Apli\OAuth2\Client\Support\Utils\Constants;
use GuzzleHttp\RequestOptions;

/**
 * Class RdStationAccount
 * @package Apli\Integration\RdStation
 */
class RdStationAccount extends AbstractResourceOwner
{
    use WithRequestFactoryTrait,
        WithBearerAuthorizationTrait,
        WithFillPropertyTrait,
        WithCheckResponseTrait;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var AccessTokenInterface
     */
    protected $token;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var ContactService
     */
    protected $contacts;

    /**
     * @var FunnelService
     */
    protected $funnels;

    /**
     * @var EventService
     */
    protected $events;

    /**
     * RDStationAccount constructor.
     * @param array                $data
     * @param AccessTokenInterface $token
     * @param array                $options
     */
    public function __construct(array $data, AccessTokenInterface $token, array $options = [])
    {
        parent::__construct($data);

        $options = Arr::convertKeysFromSnakeToCamel($options);
        $this->fillProperties($options);
        $this->token = $token;
        $this->contacts = new ContactService($token, $options);
        $this->funnels = new FunnelService($token, $options);
        $this->events = new EventService($token, $options);
    }


    /**
     * Returns the identifier of the authorized resource owner.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->getField('name');
    }

    /**
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTrackingCode()
    {
        return $this->createRequest(Constants::METHOD_GET, $this->domain.'/marketing/tracking_code', [], $this->token);
    }

    /**
     * @return ContactService
     */
    public function contacts(): ContactService
    {
        return $this->contacts;
    }

    /**
     * @return FunnelService
     */
    public function funnels(): FunnelService
    {
        return $this->funnels;
    }

    /**
     * @return EventService
     */
    public function events(): EventService
    {
        return $this->events;
    }

    /**
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refreshToken()
    {
        $options = [
            RequestOptions::JSON => [
                Constants::CLIENT_ID => $this->clientId,
                Constants::CLIENT_SECRET => $this->clientSecret,
                Constants::REFRESH_TOKEN => $this->token->getRefreshToken()
            ]
        ];

        return $this->createRequest(Constants::METHOD_POST, $this->domain.'/auth/token', $options);
    }

    /**
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function revokeToken()
    {
        $options = [
            RequestOptions::FORM_PARAMS => ['token' => $this->token->getToken()]
        ];

        return $this->createRequest(Constants::METHOD_POST, $this->domain.'/auth/revoke', $options, $this->token);
    }
}
