<?php

namespace Apli\Integration\RdStation\Services;

use Apli\Integration\RdStation\Concerns\WithCheckResponseTrait;
use Apli\Integration\RdStation\Entities\Funnel;
use Apli\OAuth2\Client\Auth\Concerns\WithBearerAuthorizationTrait;
use Apli\OAuth2\Client\Auth\Tokens\AccessTokenInterface;
use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;
use Apli\OAuth2\Client\Support\Http\Concerns\WithRequestFactoryTrait;
use Apli\OAuth2\Client\Support\Utils\Constants;
use GuzzleHttp\RequestOptions;
use function array_filter;
use function filter_var;
use function in_array;
use function urlencode;

/**
 * Class FunnelService
 * @package Apli\Integration\RdStation\Services
 */
class FunnelService
{
    use WithRequestFactoryTrait,
        WithBearerAuthorizationTrait,
        WithCheckResponseTrait,
        WithFillPropertyTrait;

    /**
     * @var string
     */
    public $domain;

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
     * Contacts constructor.
     * @param AccessTokenInterface $token
     * @param array                $options
     */
    public function __construct(AccessTokenInterface $token, array $options = [])
    {
        $this->fillProperties($options);
        $this->token = $token;
    }

    /**
     * @param string $identifier Contact email or uuid
     * @param string $funnelName Funnel name
     * @return Funnel
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByIdentifier(string $identifier, string $funnelName = 'default'): Funnel
    {
        $url = $this->buildUri($identifier, $funnelName);
        $funnel = new Funnel($this->createRequest(Constants::METHOD_GET, $url, [], $this->token));
        return $funnel->setName($funnelName);
    }

    /**
     * @param string $identifier Contact email or uuid
     * @param string $funnelName Funnel name
     * @return string
     */
    protected function buildUri(string $identifier, string $funnelName = 'default'): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $identifier = 'email:'.urlencode($identifier);
        }
        return $this->domain.'/platform/contacts/'.$identifier.'/funnels/'.$funnelName;
    }

    /**
     * @param string $identifier Contact email or uuid
     * @param Funnel $funnel
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $identifier, Funnel $funnel)
    {
        $removeFields = ['fit', 'interest', 'name'];

        $data = array_filter($funnel->toArray(), static function ($key) use ($removeFields) {
            return in_array($key, $removeFields, false) === false;
        }, ARRAY_FILTER_USE_KEY);

        $url = $this->buildUri($identifier, $funnel->getName());
        return $this->createRequest(Constants::METHOD_PATCH, $url, [
            RequestOptions::JSON => $data
        ], $this->token);
    }
}
