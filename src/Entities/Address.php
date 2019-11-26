<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;

/**
 * Class Address
 * @package Apli\Integration\RdStation\Entities
 */
class Address
{
    use WithFillPropertyTrait;

    /**
     * @var null|string
     */
    protected $city;
    /**
     * @var null|string
     */
    protected $state;
    /**
     * @var null|string
     */
    protected $country;

    /**
     * Contact constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->fillProperties($data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'country' => $this->getCountry()
        ];
    }

    /**
     * @return null|string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param null|string $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return null|string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param null|string $state
     */
    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return null|string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param null|string $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }
}
