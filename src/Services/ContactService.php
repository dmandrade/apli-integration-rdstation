<?php

namespace Apli\Integration\RdStation\Services;

use Apli\Integration\RdStation\Concerns\WithCheckResponseTrait;
use Apli\Integration\RdStation\Entities\Contact;
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
 * Class ContactService
 * @package Apli\Integration\RdStation\Services
 */
class ContactService
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
     * @return Contact
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByIdentifier(string $identifier): Contact
    {
        $url = $this->buildUri($identifier);
        return new Contact($this->createRequest(Constants::METHOD_GET, $url, [], $this->token));
    }

    /**
     * @param string $identifier Contact email or uuid
     * @return string
     */
    protected function buildUri(string $identifier): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $identifier = 'email:'.urlencode($identifier);
        }
        return $this->domain.'/platform/contacts/'.$identifier;
    }

    /**
     * @param Contact $contact
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function patch(Contact $contact)
    {
        $removeFields = ['uuid'];
        $identifier = $contact->getUuid();

        if ($identifier === null) {
            $removeFields = ['email'];
            $identifier = $contact->getEmail();
        }
        $removeFields[] = 'extra_emails';

        $data = array_filter($contact->toArray(), static function ($key) use ($removeFields) {
            return in_array($key, $removeFields, false) === false;
        }, ARRAY_FILTER_USE_KEY);

        $url = $this->buildUri($identifier);
        return $this->createRequest(Constants::METHOD_PATCH, $url, [
            RequestOptions::JSON => $data
        ], $this->token);
    }
}
