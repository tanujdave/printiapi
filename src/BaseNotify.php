<?php

namespace Printi\NotifyBundle;

class BaseNotify
{
    /** @var array $config */
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
}
