<?php

namespace PrintiApi\Request;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;

class Request
{
    protected $app;
    protected $response;
    protected $statusCode;
    protected $headers         = [];
    protected $responseHeaders = [];
    protected $body;

    public function exec()
    {
        try {
            // Create default HandlerStack
            $stack = HandlerStack::create();

            // Add this middleware to the top with `push`
            $stack->push(new CacheMiddleware(
                new PrivateCacheStrategy(
                    new DoctrineCacheStorage(
                        new ChainCache([
                            new ArrayCache(),
                            new FilesystemCache('/tmp/'),
                        ])
                    )
                )
            ), 'cache');

            // Initialize the client with the handler option
            $client = new GuzzleClient(['handler' => $stack, 'select_timeout' => static::CONNECTION_TIMEOUT]);

            $response = $client->request($this->getMethodURL(), $this->getUrl(), [
                'headers' => $this->headers,
                'query'   => $this->query,
                'body'    => $this->getBody(),
            ]);

            $this->response        = $response->getBody()->getContents();
            $this->statusCode      = $response->getStatusCode();
            $this->responseHeaders = $response->getHeaders();
        } catch (ClientException $e) {
            $this->response   = $e->getResponse()->getBody()->getContents();
            $this->statusCode = 400;
            //var_dump($this->getUrl(), $this->getBody());
        } catch (ServerException $e) {
            echo ($e->getResponse()->getBody()->getContents());
            exit;
        }

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function addHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    protected function addQuery($key, $value)
    {
        $this->query[$key] = $value;

        return $this;
    }

    protected function addUrlParam($value)
    {
        $this->urlParams[] = $value;

        return $this;
    }

    protected function setUrlParams(array $params)
    {
        $this->urlParams = $params;

        return $this;
    }

}
