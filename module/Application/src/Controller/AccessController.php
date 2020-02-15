<?php

namespace Application\Controller;

class AccessController extends ApiController
{
    public function tokenAction()
    {
        $pars = $this->processBodyContent($this->getRequest());
        
        /**
         * process your data and validate it against database table
         */

         /*
            sub (subject) = Entidade à quem o token pertence, normalmente o ID do usuário;
            iss (issuer) = Emissor do token;
            exp (expiration) = Timestamp de quando o token irá expirar;
            iat (issued at) = Timestamp de quando o token foi criado;
            aud (audience) = Destinatário do token, representa a aplicação que irá usá-lo.
        */  
        
        // generate token if valid user
        $payload = [
            'sub' => 1, 
            'name' => 'Fabio de Souza', 
            'admin' => false
        ];

        $data = [];
        $data['token'] = $this->generateJwtToken($payload);
        $data['message'] = 'Logged in successfully.';

        return $this->createResponse($data);

    }
}
