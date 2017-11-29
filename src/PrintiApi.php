<?php

namespace PrintiApi;


use PrintiApi\BaseApi\BaseApi;

class PrintiApi extends BaseApi
{
    protected $response;

    public function __construct(array $options)
    {
        $this->setApiCreds($options);
    }

    public static function init(array $options)
    {
        return new PrintiApi($options);
    }

    public function get($url, $params = [], $headers = [], $query = [])
    {
        $this->setMethodURL('GET');
        $this->setUrl($url, $params);
        $this->addHeaders($headers);
        $this->addQuerys($query);

        $this->exec();

        return $this->response();
    }

    public function post($url, $params = [], $postRawData = [], $headers = [])
    {
        $this->setMethodURL('POST');
        $this->setUrl($url, $params);
        $this->addHeaders($headers);
        $this->setBody($postRawData);
        $this->exec();

        return $this->response();
    }

    public function put($url, $params = [], $postRawData = [], $headers = [])
    {
        $this->setMethodURL('PUT');
        $this->setUrl($url, $params);
        $this->addHeaders($headers);
        $this->setBody($postRawData);
        $this->exec();

        return $this->response();
    }

    public function delete($url, $params = [], $headers = [])
    {
        $this->setMethodURL('delete');
        $this->setUrl($url, $params);
        $this->addHeaders($headers);
        $this->exec();

        return $this->response();
    }
}
