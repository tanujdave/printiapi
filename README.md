ApiClient, PHP HTTP client
==========================

ApiClient is a PHP HTTP client that makes it easy to send HTTP requests and
trivial to integrate with web services.

- Simple interface for building query strings, POST requests, uploading JSON data,
  etc...
- Abstracts away the underlying HTTP transport, allowing you to write
  environment and transport agnostic code; i.e., no hard dependency on cURL,
  PHP streams, sockets, or non-blocking event loops.

## Installing ApiClient

The recommended way to install ApiClient is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Guzzle:

```bash
php composer.phar require guzzlehttp/guzzle
```

Next, run the Composer command to install the latest stable version of ApiClient:

```bash
php composer.phar require printi/api-client
```

You can then later update ApiClient using composer:

 ```bash
composer.phar update
```

## User Guide

We are having few parameters which are configured for different environments viz. local, dev and prod in config directory for different projects. 

For eg:
```yaml
parameters:
    api_client:
        connection_timeout:   2.0
        allowed_methods:      ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']

        project_key:
            host:     "http://project-domain.com/"
            headers:
                #You can mention key-value pair here,
                # internal code will replace "_" with "-" in key
                token:  ThisIsYourSecretTokenForApiAuthorization
``` 

In above yaml file, ``api_client``, ``connection_timeout``, ``allowed_methods``, ``host``, ``headers`` are keywords which are to be used as it is. ApiClient reads those values based on those keys.
``your_project_key`` key are the project related parameters, If you need to include parameters for your project please make a request.

You can make use of this plugin in following way:

For Eg:
```php
$env = $container->get('kernel')->getEnvironment();     //local, dev or prod
$apiClient = new ApiClient('project_key', $env);
return $apiClient->get(
    '/v1/products',             // Route endpoint
    [],                         // url parameters to be replaced in route endpoint
    ['page'=> 1],               // query parameters
    ['x-total-count' => 'Yes']  // headers other than default set in parameters.yaml
);
``` 

In Project settings, ``host`` is mandatory, failing which the ApiClient will throw error. You just need to mention Base URI/Host name in ``host``.
``headers`` is optional. Api Client passes all headers parameters in config by default with every request.
Also you can pass custom headers in get(), post() etc request as mentioned in example above.

Note: ApiClient always applies 'accept' = 'application/json' and 'content-type' = 'application/json' in headers by default.