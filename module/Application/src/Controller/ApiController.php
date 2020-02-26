<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\Mvc\MvcEvent; 
use Laminas\View\Model\JsonModel;
use Firebase\JWT\JWT;
use Laminas\EventManager\EventManagerInterface;
use Application\Jwt\Payload;

class ApiController extends AbstractRestfulController
{

    /**
     * @var Integer $httpStatusCode Define Api Response code.
     */
    public $httpStatusCode = 200;

    /**
     * @var array $apiResponse Define response for api
     */
    public $apiResponse;

    /**
     *
     * @var type string 
     */
    public $token;

    /**
     *
     * @var type Object or Array
     */
    public $tokenPayload;

    /**
     * set Event Manager to check Authorization
     * @param \Laminas\EventManager\EventManagerInterface $events
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        $events->attach('dispatch', [$this, 'checkAuthorization'], 10);
    }

    /**
     * This Function call from eventmanager to check authntication and token validation
     * @param type $event
     * 
     */
    public function checkAuthorization($event)
    {
        $request = $event->getRequest();
        $method  = $request->getMethod();
        
        $response = $event->getResponse();
        $isAuthorizationRequired = $event->getRouteMatch()->getParam('isAuthorizationRequired');
        $methods = $event->getRouteMatch()->getParam('methodsAuthorization');
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $event->setParam('config', $config);

        if (!in_array($method, $methods)) {
            return;
        }
        
        if (isset($config['ApiRequest'])) {
            $responseStatusKey = $config['ApiRequest']['responseFormat']['statusKey'];
            if (!$isAuthorizationRequired) {
                return;
            }
            $jwtToken = $this->findJwtToken($request);
            if ($jwtToken) {
                $this->token = $jwtToken;
                $this->decodeJwtToken();
                if (is_object($this->tokenPayload)) {
                    return;
                }
                $response->setStatusCode(400);
                $jsonModelArr = [$responseStatusKey => $config['ApiRequest']['responseFormat']['statusNokText'], 
                    $config['ApiRequest']['responseFormat']['resultKey'] => [$config['ApiRequest']['responseFormat']['errorKey'] => 
                        $this->tokenPayload]];
            } else {
                $response->setStatusCode(401);
                $jsonModelArr = [$responseStatusKey => $config['ApiRequest']['responseFormat']['statusNokText'], 
                    $config['ApiRequest']['responseFormat']['resultKey'] => [$config['ApiRequest']['responseFormat']['errorKey'] => 
                        $config['ApiRequest']['responseFormat']['authenticationRequireText']]];
            }
        } else {
            $response->setStatusCode(400);
            $jsonModelArr = ['status' => 'NOK', 'result' => ['error' => 'Require copy this file config\autoload\restapi.global.php']];
        }

        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $view = new JsonModel($jsonModelArr);
        $response->setContent($view->serialize());
        return $response;
    }

    /**
     * Check Request object have Authorization token or not 
     * @param type $request
     * @return type String
     */
    public function findJwtToken($request)
    {
        $jwtToken = $request->getHeaders("Authorization") ? $request->getHeaders("Authorization")->getFieldValue() : '';
        if ($jwtToken) {
            $jwtToken = trim(trim($jwtToken, "Bearer"), " ");
            return $jwtToken;
        }
        if ($request->isGet()) {
            $jwtToken = $request->getQuery('token');
        }
        if ($request->isPost()) {
            $jwtToken = $request->getPost('token');
        }
        return $jwtToken;
    }

    /**
     * contain user information for createing JWT Token
     */
    protected function generateJwtToken($payload)
    {
        if (!is_array($payload) && !is_object($payload)) {
            $this->token = false;
            return false;
        }
        $this->tokenPayload = $payload;
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        $this->token = JWT::encode($this->tokenPayload, $cypherKey, $tokenAlgorithm);
        return $this->token;
    }

    /**
     * contain encoded token for user.
     */
    protected function decodeJwtToken()
    {
        if (!$this->token) {
            $this->tokenPayload = false;
        }
        $config = $this->getEvent()->getParam('config', false);
        $cypherKey = $config['ApiRequest']['jwtAuth']['cypherKey'];
        $tokenAlgorithm = $config['ApiRequest']['jwtAuth']['tokenAlgorithm'];
        try {
            $decodeToken = JWT::decode($this->token, $cypherKey, [$tokenAlgorithm]);
            $this->tokenPayload = $decodeToken;
        } catch (\Exception $e) {
            $this->tokenPayload = $e->getMessage();
        }
    }

    /**
     * @return ?object
     */
    protected function getTokenPayload() : ?object
    {
        return $this->tokenPayload;
    } 

    /**
     * @return Payload
     */
    protected function getPayload() : Payload
    {
        $payload = new Payload($this->tokenPayload);        
        return $payload;
    } 

    /**
     * Create Response for api Assign require data for response and check is valid response or give error
     * 
     * @param array $data
     * @return \Laminas\View\Model\JsonModel 
     * 
     */
    public function createResponse(array $apiResponse = [])
    {   
        $numArgs = count($apiResponse);

        $config = $this->getEvent()->getParam('config', false);
        $event = $this->getEvent();
        $response = $event->getResponse();

        if ($numArgs > 0) {
            $response->setStatusCode($this->httpStatusCode);
        } else {
            $this->httpStatusCode = 500;
            $response->setStatusCode($this->httpStatusCode);
            $errorKey = $config['ApiRequest']['responseFormat']['errorKey'];
            $defaultErrorText = $config['ApiRequest']['responseFormat']['defaultErrorText'];
            $apiResponse[$errorKey] = $defaultErrorText;
        }

        $statusKey = $config['ApiRequest']['responseFormat']['statusKey'];
        
        $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusNokText'];
        if ($this->httpStatusCode == 200) {
            $sendResponse[$statusKey] = $config['ApiRequest']['responseFormat']['statusOkText'];
        } 

        $sendResponse[$config['ApiRequest']['responseFormat']['resultKey']] = $apiResponse;
        return new JsonModel($sendResponse);
    }
}