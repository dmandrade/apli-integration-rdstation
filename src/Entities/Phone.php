<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;

/**
 * Class Phone
 * @package Apli\Integration\RdStation\Entities
 */
class Phone
{
    use WithFillPropertyTrait;

    /**
     * @var null|string
     */
    protected $personalPhone;
    /**
     * @var null|string
     */
    protected $mobilePhone;

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
            'personal_phone' => $this->getPersonalPhone(),
            'mobile_phone' => $this->getMobilePhone(),
        ];
    }

    /**
     * @return null|string
     */
    public function getPersonalPhone(): ?string
    {
        return $this->personalPhone;
    }

    /**
     * @param null|string $personalPhone
     */
    public function setPersonalPhone(?string $personalPhone): void
    {
        $this->personalPhone = $personalPhone;
    }

    /**
     * @return null|string
     */
    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    /**
     * @param null|string $mobilePhone
     */
    public function setMobilePhone(?string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }
}
