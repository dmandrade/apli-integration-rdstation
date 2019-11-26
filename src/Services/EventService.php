<?php

namespace Apli\Integration\RdStation\Services;

use Apli\Integration\RdStation\Concerns\WithCheckResponseTrait;
use Apli\Integration\RdStation\Entities\Event;
use Apli\Integration\RdStation\Entities\Funnel;
use Apli\OAuth2\Client\Auth\Concerns\WithBearerAuthorizationTrait;
use Apli\OAuth2\Client\Auth\Tokens\AccessTokenInterface;
use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;
use Apli\OAuth2\Client\Support\Http\Concerns\WithRequestFactoryTrait;
use Apli\OAuth2\Client\Support\Utils\Constants;
use GuzzleHttp\RequestOptions;
use function array_map;
use function count;

/**
 * Class EventService
 * @package Apli\Integration\RdStation\Services
 */
class EventService
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
     * @param bool $isBatch
     * @return string
     */
    protected function buildUri($isBatch = false): string
    {
        return $this->domain.'/platform/events'.($isBatch ? '/batch' : '');
    }

    /**
     * @param Event ...$events
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(Event ...$events)
    {
        $data = $this->prepareEvents($events);
        $url = $this->buildUri(count($events) > 1);
        return $this->createRequest(Constants::METHOD_POST, $url, [
            RequestOptions::JSON => $data
        ], $this->token);
    }

    /**
     * @param array $events
     * @return array
     */
    protected function prepareEvents(array $events): array
    {
        $events = array_map(static function (Event $event) {
            return $event->toArray();
        }, $events);
        if (count($events) > 1) {
            return $events;
        }

        return reset($events);
    }
}
