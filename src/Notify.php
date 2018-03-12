<?php

namespace Printi\NotifyBundle;

class Notify extends BaseNotify
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function notifyOnTransition(string $transition, array $body)
    {

    }

    public static function getNotificationPriority(string $type, string $key)
    {

    }

}