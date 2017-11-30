<?php

namespace PrintiApi\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class Request
{
    protected $app;
    protected $currentHttpMethod = false;
    protected $additionalHeaders = [];
    protected $custom_url = null;
    protected $urlParams = [];
    protected $customOptions = [];
    protected $response;
    protected $statusCode;
    protected $body = [];
    protected $debug_info = null;
    protected $exec_start_time;
    protected $responseHeaders = [];

    const CONNECTION_TIMEOUT  = 2.0;
    const HTTP_METHOD         = 'GET';
    const HTTP_STATUS_SUCCESS = 200;
    const HTTP_STATUS_FAILURE = 400;
    const URL                 = null;

    /**
     * @param bool $debug when true, we'll fill the debug info (sent/receive data/status)
     *
     * @return $this
     */
    public function exec($debug = false)
    {

        $this->exec_start_time = new \DateTime();
        $this->debug_info      = new \stdClass();

        try {
            $httpMethod = $this->getMethodUrl() ? $this->getMethodUrl() : static::HTTP_METHOD;

            $url = $this->getUrl() ?: static::URL;

            if ($this->urlParams) {
                ksort($this->urlParams);
                $url = vsprintf($url, $this->urlParams);
            }

            $options = [];

            $body            = $this->getBody();
            $options['body'] = !is_array($body) ? $body : \GuzzleHttp\json_encode($body);

            if (count($this->additionalHeaders) > 0) {
                $options['headers'] = $this->additionalHeaders;
            }
            $options = array_merge($options, $this->customOptions);

            if ($debug) {
                $this->debug_info->method    = $httpMethod;
                $this->debug_info->url       = $url;
                $this->debug_info->sent_data = $options;
            }

            $client = new GuzzleClient(['timeout' => self::CONNECTION_TIMEOUT]);

            $response = $client->request($httpMethod, $url, $options);

            $this->response   = $response->getBody();
            $this->statusCode = $response->getStatusCode();

        } catch (ClientException $ge) {
            $this->response   = 500 === $ge->getResponse()->getStatusCode() ? $ge->getResponse()->getReasonPhrase() : ClientException::getResponseBodySummary(
                $ge->getResponse()
            );
            $this->statusCode = 400;
        } catch (\Exception $e) {
            $this->response   = $e->getMessage();
            $this->statusCode = 400;
        }
        if ($debug) {
            $this->debug_info->response_data   = $this->response;
            $this->debug_info->response_status = $this->statusCode;
        }

        return $this;
    }

    public function getDebugInfo()
    {
        return $this->debug_info;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function addHeader($key, $value)
    {
        $this->additionalHeaders[$key] = $value;

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

    /**
     * Listed options in Guzzle docs and injected in resquest methods.
     *
     * @param array $options
     *
     * @return $this
     */
    protected function setCustomOptions(array $options)
    {
        $this->customOptions = $options;

        return $this;
    }

    /**
     * Define a body content to be sent.
     *
     * @param bool|string $content
     *
     * @return $this
     */
    protected function setSentBody($content = false)
    {
        $this->body = $content;

        return $this;
    }

    protected function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }
}
