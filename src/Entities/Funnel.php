<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;

/**
 * Class Funnel
 * @package Apli\Integration\RdStation\Entities
 */
class Funnel
{
    use WithFillPropertyTrait;

    public const STAGE_LEAD = 'Lead';
    public const STAGE_QUALIFIED_LEAD = 'Qualified Lead';
    public const STAGE_CLIENT = 'Client';

    /**
     * @var string
     */
    protected $name;
    /**
     * @var null|string
     */
    protected $lifecycleStage;
    /**
     * @var boolean
     */
    protected $opportunity;
    /**
     * @var null|string
     */
    protected $contactOwnerEmail;
    /**
     * @var int
     */
    protected $fit;
    /**
     * @var int
     */
    protected $interest;

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
            'name' => $this->getName(),
            'lifecycle_stage' => $this->getLifecycleStage(),
            'opportunity' => $this->isOpportunity(),
            'contact_owner_email' => $this->getContactOwnerEmail(),
            'fit' => $this->getFit(),
            'interest' => $this->getInterest(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Funnel
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLifecycleStage(): ?string
    {
        return $this->lifecycleStage;
    }

    /**
     * @param null|string $lifecycleStage
     * @return Funnel
     */
    public function setLifecycleStage(?string $lifecycleStage): self
    {
        $this->lifecycleStage = $lifecycleStage;
        return $this;
    }

    /**
     * @return bool
     */
    public function isOpportunity(): bool
    {
        return $this->opportunity;
    }

    /**
     * @param bool $opportunity
     * @return Funnel
     */
    public function setOpportunity(bool $opportunity): self
    {
        $this->opportunity = $opportunity;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getContactOwnerEmail(): ?string
    {
        return $this->contactOwnerEmail;
    }

    /**
     * @param null|string $contactOwnerEmail
     * @return Funnel
     */
    public function setContactOwnerEmail(?string $contactOwnerEmail): self
    {
        $this->contactOwnerEmail = $contactOwnerEmail;
        return $this;
    }

    /**
     * @return int
     */
    public function getFit(): int
    {
        return $this->fit;
    }

    /**
     * @param int $fit
     * @return Funnel
     */
    public function setFit(int $fit): self
    {
        $this->fit = $fit;
        return $this;
    }

    /**
     * @return int
     */
    public function getInterest(): int
    {
        return $this->interest;
    }

    /**
     * @param int $interest
     * @return Funnel
     */
    public function setInterest(int $interest): self
    {
        $this->interest = $interest;
        return $this;
    }
}
