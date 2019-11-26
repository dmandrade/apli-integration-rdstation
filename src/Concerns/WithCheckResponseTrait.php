<?php

namespace Apli\Integration\RdStation\Concerns;

use Apli\OAuth2\Client\Support\DataStructures\Arr;
use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait WithCheckResponseTrait
 * @package Apli\Integration\RdStation\Concerns
 */
trait WithCheckResponseTrait
{
    /**
     * @param ResponseInterface $response
     * @param mixed             $data
     * @throws Exception
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() !== 200) {
            $errors = $data['errors'][0] ?? $data['errors'];
            throw new Exception(Arr::get($errors, 'error_message'));
        }
    }
}
