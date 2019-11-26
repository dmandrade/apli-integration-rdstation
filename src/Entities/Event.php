<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Arr;

/**
 * Class Event
 * @package Apli\Integration\RdStation\Entities
 */
class Event
{

    public const FAMILY_CDP = 'CDP';
    public const TYPE_CONVERSION = 'CONVERSION';
    public const TYPE_OPPORTUNITY = 'OPPORTUNITY';
    public const TYPE_SALE = 'SALE';
    public const TYPE_OPPORTUNITY_LOST = 'OPPORTUNITY_LOST';
    public const TYPE_ORDER_PLACED = 'ORDER_PLACED';
    public const TYPE_ORDER_PLACED_ITEM = 'ORDER_PLACED_ITEM';
    public const TYPE_CART_ABANDONED = 'CART_ABANDONED';
    public const TYPE_CART_ABANDONED_ITEM = 'CART_ABANDONED_ITEM';
    public const TYPE_CHAT_STARTED = 'CHAT_STARTED';
    public const TYPE_CHAT_FINISHED = 'CHAT_FINISHED';
    public const TYPE_CALL_FINISHED = 'CALL_FINISHED';
    public const TYPE_MEDIA_PLAYBACK_STARTED = 'MEDIA_PLAYBACK_STARTED';
    public const TYPE_MEDIA_PLAYBACK_STOPPED = 'MEDIA_PLAYBACK_STOPPED';

    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $family;
    /**
     * @var array
     */
    protected $payload;

    /**
     * Event constructor.
     * @param string $family
     * @param string $type
     * @param array  $payload
     */
    public function __construct(string $family, string $type, array $payload = [])
    {
        $this->family = $family;
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getPayloadField(string $key, $default = null)
    {
        return Arr::get($this->payload, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setPayloadField(string $key, $value): void
    {
        $this->payload[$key] = $value;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->getType(),
            'event_family' => $this->getFamily(),
            'payload' => $this->getPayload()
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFamily(): string
    {
        return $this->family;
    }

    /**
     * @param string $family
     */
    public function setFamily(string $family): void
    {
        $this->family = $family;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
