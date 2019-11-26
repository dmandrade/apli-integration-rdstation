<?php

namespace Apli\Integration\RdStation\Provider;

use Apli\Integration\RdStation\Exceptions\RDStationIdentityProviderException;
use Apli\OAuth2\Client\Auth\Concerns\WithBearerAuthorizationTrait;
use Apli\OAuth2\Client\Auth\Tokens\AccessTokenInterface;
use Apli\OAuth2\Client\Provider\AbstractProvider;
use Apli\OAuth2\Client\Provider\Concerns\WithResourceOwnerTrait;
use Apli\OAuth2\Client\Provider\ResourceOwnerInterface;
use Apli\OAuth2\Client\Support\Utils\Constants;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RDStation
 * @package Apli\Integration\RdStation
 */
class RdStation extends AbstractProvider
{
    use WithResourceOwnerTrait, WithBearerAuthorizationTrait;

    /**
     * @var string
     */
    protected $domain = 'https://api.rd.services';

    /**
     * Returns the name of this provider
     *
     * @return string
     */
    public function getName(): string
    {
        return 'rdstation';
    }

    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain.'/auth/dialog';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->domain.'/auth/token';
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessTokenInterface $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessTokenInterface $token): string
    {
        return $this->domain.'/marketing/account_info';
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * Returns authorization parameters based on provided options.
     *
     * @param  array $options
     * @return array Authorization parameters
     */
    protected function getAuthorizationParameters(array $options): array
    {
        return [
            'redirect_url' => $this->redirectUri,
            'client_id' => $this->clientId,
        ];
    }

    /**
     * Checks a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array|string      $data
     * @throws \Apli\OAuth2\Client\Provider\Exceptions\IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw RDStationIdentityProviderException::clientException($response, $data);
        }

        if (isset($data['error'])) {
            throw RDStationIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param  array                $response
     * @param  AccessTokenInterface $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessTokenInterface $token): ResourceOwnerInterface
    {
        return new RdStationAccount($response, $token, [
            'domain' => $this->domain,
            Constants::CLIENT_ID => $this->clientId,
            Constants::CLIENT_SECRET => $this->clientSecret,
            self::HTTP_CLIENT => $this->getHttpClient(),
        ]);
    }
}
