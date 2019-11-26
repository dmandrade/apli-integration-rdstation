<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;

/**
 * Class SocialNetWork
 * @package Apli\Integration\RdStation\Entities
 */
class SocialNetWork
{
    use WithFillPropertyTrait;
    /**
     * @var null|string
     */
    protected $twitter;
    /**
     * @var null|string
     */
    protected $facebook;
    /**
     * @var null|string
     */
    protected $linkedin;

    /**
     * SocialNetWork constructor.
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
            'twitter' => $this->getTwitter(),
            'facebook' => $this->getFacebook(),
            'linkedin' => $this->getLinkedin(),
        ];
    }

    /**
     * @return null|string
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * @param null|string $twitter
     */
    public function setTwitter(?string $twitter): void
    {
        $this->twitter = $twitter;
    }

    /**
     * @return null|string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @param null|string $facebook
     */
    public function setFacebook(?string $facebook): void
    {
        $this->facebook = $facebook;
    }

    /**
     * @return null|string
     */
    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    /**
     * @param null|string $linkedin
     */
    public function setLinkedin(?string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }
}
