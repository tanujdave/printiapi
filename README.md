# Printi Api Wrapper

A Printi common api for their microservices.

# Install

Via Composer

    $ composer require tanujdave/printiapi
    
# Usage
    
    $options = [
        'host' => 'http://test-url.com/' // base url of Api        
    ];
    
    PrintiApi::init($options)->get($url, $params = [], $headers = [], $query = [])
    PrintiApi::init($options)->post($url, $params = [], $postRawData = [], $headers = []);
    PrintiApi::init($options)->put($url, $params = [], $postRawData = [], $headers = []);
    PrintiApi::init($options)->delete($url, $params = [], $headers = []);