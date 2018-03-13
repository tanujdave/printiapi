<?php

namespace Printi\NotifyBundle;

class Notify extends BaseNotify
{

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function notifyOnTransition(string $transition, array $body)
    {
        var_dump($this->config);

    }

    public function getNotificationPriority(string $type, string $key)
    {

    }

}
