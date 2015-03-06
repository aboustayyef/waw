<?php namespace waw\Crawling;

// Based on: https://gist.github.com/abailiss/1196916 ,comment by damienalexander,

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\History\HistoryPlugin;

class UrlResolver
{
    private $client, $history, $url;

    function __construct($url){
        $this->url = $url;
        $this->client   = new GuzzleClient($url);
        //$this->history  = new HistoryPlugin();
    }

    public function resolve(){

        //$this->client->addSubscriber($this->history);
        $request = $this->client->get($this->url, ['cookies' => true]);
        $response = $request->send();
        if (!$response->isSuccessful()) {
            return 'Could Not resolve URL ' . $this->url;
        }
        if (@$response->getEffectiveUrl()){
          return $response->getEffectiveUrl();
        }else {
          return false;
        }
    }
}

class UrlDebugger
{
    private $url;
    public function __construct($url){
        $this->url = $url;
    }

    public function debug(){
        $client = new GuzzleClient($this->url);
        $request = $client->get($this->url);
        $history = new HistoryPlugin();
        $request->addSubscriber($history);
        $response = $request->send();

        // Get the last redirect URL or the URL of the request that received
        // this response
        //echo $response->getEffectiveUrl() . "\n";
        die($response->getEffectiveUrl());
        // Get the number of redirects
        echo $response->getRedirectCount(). "\n";

        // Iterate over each sent request and response
        foreach ($history->getAll() as $transaction) {
            // Request object
            echo $transaction['request']->getUrl() . "\n";
            // Response object
            echo $transaction['response']->getEffectiveUrl() . "\n";
        }
        // Or, simply cast the HistoryPlugin to a string to view each request and response
        echo $history. "\n";
    }
}
