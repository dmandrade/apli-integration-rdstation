<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 23/11/2019
 * Time: 16:46
 */

namespace Apli\Integration\RdStation\Exceptions;

use Apli\OAuth2\Client\Provider\Exceptions\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class RDStationIdentityProviderException extends IdentityProviderException
{
    /**
     * Creates client exception from response.
     *
     * @param  ResponseInterface $response
     * @param  array             $data Parsed response data
     *
     * @return IdentityProviderException
     */
    public static function clientException(ResponseInterface $response, $data): IdentityProviderException
    {
        return static::fromResponse(
            $response,
            $data['message'] ?? $response->getReasonPhrase()
        );
    }

    /**
     * Creates identity exception from response.
     *
     * @param  ResponseInterface $response
     * @param  string            $message
     *
     * @return IdentityProviderException
     */
    protected static function fromResponse(ResponseInterface $response, $message = null): IdentityProviderException
    {
        return new static($message, $response->getStatusCode(), (string)$response->getBody());
    }

    /**
     * Creates oauth exception from response.
     *
     * @param  ResponseInterface $response
     * @param  array             $data Parsed response data
     *
     * @return IdentityProviderException
     */
    public static function oauthException(ResponseInterface $response, $data): IdentityProviderException
    {
        return static::fromResponse(
            $response,
            $data['error'] ?? $response->getReasonPhrase()
        );
    }
}
