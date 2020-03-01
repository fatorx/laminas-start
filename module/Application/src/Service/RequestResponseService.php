<?php

namespace Application\Service;

use Laminas\Mvc\Application;

class RequestResponseService extends BaseService
{
    /**
     * @param Application $app
     */
    public function register(Application $app, string $dateEnter)
    {
        $request = $app->getRequest();
        $response = $app->getResponse();
        
        $dateExit  = (new \Datetime())->format('Y-m-d H:i:s');

        $method  = $request->getMethod();
        $protocol = $request->getUri()->getScheme();
        $host     = $request->getUri()->getHost();
        $port     = $request->getUri()->getPort();
        $path     = $request->getUri()->getPath();
        $queryString = $request->getUri()->getQuery();
        $content = $request->getContent();

        $headers = $request->getHeaders()->toArray();
        $headerJson = json_encode($headers);

        $ipClient = $request->getServer()->get('REMOTE_ADDR');
        $bodyResponse = $response->getBody();

        $pars = [
            'date_enter' =>  $dateEnter,   
            'date_exit' =>  $dateExit,   
            'method' =>  $method,   
            'scheme' =>  $protocol,   
            'host' =>  $host,   
            'port' =>  $port,   
            'path_info' =>  $path,   
            'query_string' =>  $queryString,   
            'received_data' =>  $content,   
            'headers' =>  $headerJson,   
            'ip' =>  $ipClient,   
            'response' =>  $bodyResponse,   
        ];
        
        $this->insert('requests', $pars);
    }
}