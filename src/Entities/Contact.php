<?php

namespace Apli\Integration\RdStation\Entities;

use Apli\OAuth2\Client\Support\DataStructures\Arr;
use Apli\OAuth2\Client\Support\DataStructures\Concerns\WithFillPropertyTrait;
use Apli\OAuth2\Client\Support\Str;

/**
 * Class Contact
 * @package Apli\Integration\RdStation\Entities
 */
class Contact
{
    use WithFillPropertyTrait;

    /**
     * @var null|string
     */
    protected $uuid;
    /**
     * @var null|string
     */
    protected $name;
    /**
     * @var null|string
     */
    protected $email;
    /**
     * @var null|string
     */
    protected $bio;
    /**
     * @var null|string
     */
    protected $website;
    /**
     * @var null|string
     */
    protected $jobTitle;
    /**
     * @var array
     */
    protected $tags;
    /**
     * @var array
     */
    protected $extraEmails;
    /**
     * @var Address
     */
    protected $address;
    /**
     * @var Phone
     */
    protected $phone;
    /**
     * @var SocialNetWork
     */
    protected $socialNetwork;
    /**
     * @var array
     */
    protected $customFields;

    /**
     * Contact constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $addressFields = ['city', 'state', 'country'];
        $mobileFields = ['personalPhone', 'mobilePhone'];
        $networkFields = ['twitter', 'facebook', 'linkedin'];

        $customFields = array_filter($data, static function ($key) {
            return Str::startsWith($key, 'cf_');
        }, ARRAY_FILTER_USE_KEY);
        $this->customFields = Arr::mapWithKeys($customFields, static function ($key, $value) {
            return [str_replace('cf_', '', $key) => $value];
        });

        $formatedData = Arr::convertKeysFromSnakeToCamel($data);
        $this->fillProperties(Arr::except($formatedData, array_merge($addressFields, $mobileFields, $networkFields)));
        $this->address = new Address(Arr::only($formatedData, $addressFields));
        $this->phone = new Phone(Arr::only($formatedData, $mobileFields));
        $this->socialNetwork = new SocialNetWork(Arr::only($formatedData, $networkFields));
    }

    /**
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getCustomField(string $key, $default = null)
    {
        return Arr::get($this->customFields, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setCustomField(string $key, $value): void
    {
        $this->customFields[$key] = $value;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            [
                'uuid' => $this->getUuid(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'bio' => $this->getBio(),
                'website' => $this->getWebsite(),
                'job_title' => $this->getJobTitle(),
                'tags' => $this->getTags(),
                'extra_emails' => $this->getExtraEmails(),
            ],
            $this->getAddress()->toArray(),
            $this->getPhone()->toArray(),
            $this->getSocialNetwork()->toArray(),
            Arr::mapWithKeys($this->customFields, static function ($key, $value) {
                return ['cf_'.$key => $value];
            })
        );
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @param null|string $bio
     */
    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param null|string $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return null|string
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param null|string $jobTitle
     */
    public function setJobTitle(?string $jobTitle): void
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return (array)$this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getExtraEmails(): array
    {
        return (array)$this->extraEmails;
    }

    /**
     * @param array $extraEmails
     */
    public function setExtraEmails(array $extraEmails): void
    {
        $this->extraEmails = $extraEmails;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @return Phone
     */
    public function getPhone(): Phone
    {
        return $this->phone;
    }

    /**
     * @return SocialNetwork
     */
    public function getSocialNetwork(): SocialNetwork
    {
        return $this->socialNetwork;
    }
}
