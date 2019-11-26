<?php

namespace Apli\Integration\RdStation\Entities;

/**
 * Class CustomFields
 * @package Apli\Integration\RdStation\Entities
 */
class CustomFields
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * CustomFields constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
