<?php

namespace Printi\NotifyBundle;

use Symfony\Component\Dotenv\Dotenv;

class BaseNotify
{

    private $env = 'dev';

    public function __construct()
    {
        $this->setEnvironment();
    }

    protected function setEnvironment()
    {
        try {
            $dotenv = new Dotenv();
            $dotenv->load(__DIR__.'/../../../../../.env');
            $env = getenv('APP_ENV');
        } catch (\Exception $e) {
            if (0 == $e->getCode()) {
                $errCode = 400;
            } else {
                $errCode = $e->getCode();
            }
            throw new \Exception($e->getMessage(), $errCode);
        }
        $this->env = getenv('APP_ENV');
    }

    protected function getEnvironment()
    {
        return $this->env;
    }

}