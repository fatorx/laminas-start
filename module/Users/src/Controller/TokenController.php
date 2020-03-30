<?php

namespace Users\Controller;

use Laminas\Http\Request;

use Application\Controller\ApiController;
use Users\Service\TokenService;

class TokenController extends ApiController
{
    /** 
     * @var TokenService $service 
     */
    protected $service;

    public function __construct(TokenService $service)
    {
        $this->service = $service;
    }

    public function indexAction()
    {
        /** @var Request $request */
        $request = $this->getRequest(); 
        $appKey  = $request->getHeaders()
                           ->get('App-Key')->getFieldValue(); 
                            
        $pars = $this->processBodyContent($this->getRequest());
        $user = $this->service->checkUser($pars, $appKey); 
        
        if (!$user) {
            $this->httpStatusCode = 400;
            $data = [];
            $data['message'] = 'Not Logged.';
            return $this->createResponse($data);
        }
        
        $payload = [
            'sub'   => $user->getId(), 
            'name'  => $user->getName(),
            'admin' => false,
            'issued_at'  => $this->service->getIssuedAt(),
            'expiration' => $this->service->getExpiration(),
            'audience'   => $this->service->getAudience(),
        ];

        $data = [];
        $data['token'] = $this->generateJwtToken($payload);
        $data['message'] = 'Logged in successfully.';

        return $this->createResponse($data);
    }
}
