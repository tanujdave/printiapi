<?php

namespace PrintiApi\BaseApi;

use PrintiApi\Request\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApi extends Request
{
    const CONNECTION_TIMEOUT = 2.0;

    protected $apiCreds = [];
    protected $environment;
    protected $methodURL;
    protected $url;

    public function setApiCreds(array $apiCreds)
    {
        $this->apiCreds = $apiCreds;
    }

    /**
     * @return mixed
     */
    public function exec()
    {
        $this->parseApiHeaders();
//        $this->parseApiParams();

        return parent::exec();
    }

    protected function parseApiHeaders()
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->addHeader('Accept', 'application/json');
        $this->addHeader('api-key', $this->apiCreds['api-key']);

//        $this->addHeader('Client-Locale', $this->getApp()['locale.model']->getCurrentId());
//        $this->addHeader('Client-Ip-Address', $this->getApp()['request']->server->get('REMOTE_ADDR'));
//
//        $customer = (new Auth($this->getApp()))->getAuthenticatedCustomer();
//
//        if (isset($customer['jwt'])) {
//            $this->addHeader('token', $customer['jwt']);
//        }
    }

//    protected function parseApiParams()
//    {
//        $params = $this->getApp()['api.params'];
//
//        if (null !== $params['query_params'] && 1 < count($params['query_params'])) {
//            $queryString = http_build_query($params['query_params']);
//            $this->url .= sprintf("?%s", $queryString);
//        }
//
//        if (null !== $params['headers']) {
//            $this->addHeaders($params['headers']);
//        }
//    }

    protected function addHeaders(array $headers = [])
    {
        foreach ($headers as $_key => $_value) {
            $this->addHeader($_key, $_value);
        }

        return $this;
    }
    protected function addQuerys(array $query = [])
    {
        foreach ($query as $_key => $_value) {
            $this->addQuery($_key, $_value);
        }

        return $this;
    }

    protected function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     * @return mixed
     */
    protected function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param $method
     * @return mixed
     */
    protected function setMethodURL($method)
    {
        $this->methodURL = $method;

        return $this;
    }

    /**
     * @return string
     */
    protected function getMethodURL()
    {
        return $this->methodURL;
    }

    protected function setUrl($url, $params)
    {
        $this->url = sprintf('%s/%s', $this->apiCreds['host'], $url);

        if (false === empty($params)) {
            $this->url = vsprintf($this->url, $params);
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->url;
    }

    public function response()
    {
        return new Response($this->getResponse(), $this->getStatusCode(), $this->getResponseHeaders());
    }

//    protected function getApp(): Application
//    {
//        if (null == $this->app) {
//            $this->app = $GLOBALS['app'];
//        }
//
//        return $this->app;
//    }

}
