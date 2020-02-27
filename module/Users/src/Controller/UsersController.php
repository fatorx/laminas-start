<?php

namespace Users\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Request;

use Application\Controller\ApiController;
use Users\Service\UserService;

/**
 * Class UsersController
 * @package Users\Controller
 */
class UsersController extends ApiController
{
    /** 
     * @var UserService $service 
     */
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonModel
     */
    public function get($id)
    {
        $this->preLoadMethod();
        $item = $this->service->getItem((int)$id);
        if (empty($item)) {
            $this->httpStatusCode = 400;
        }
        $data = [
            'user'    => $item,
            'method' => 'get'
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function create($data)
    {
        $status = $this->service->create($data);
        $id     = $this->service->getId();
        if (!$status) {
            $this->httpStatusCode = 400;
        }

        $data = [
            'status' => $status,
            'id'     => $id,
            'action' => 'create'
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $this->preLoadMethod();
        $item   = $this->service->update((int)$id, $data);
        $status = $this->service->getStatus();
        
        $this->httpStatusCode = ($status ? $this->httpStatusCode : 400);
        $data = [
            'item'   => $item,
            'status' => $status,
            'action' => 'update'
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function delete($id)
    {
        $this->preLoadMethod();
        $item = $this->service->delete((int)$id);
        $status = true;
        if (empty($item)) {
            $this->httpStatusCode = 400;
            $status = false;
        } 
        $data = [
            'status' => $status,
            'id' => $id,
            'action' => 'delete',
        ];
        return $this->createResponse($data);
    }

    /**
     * @return void
     */
    public function preLoadMethod()
    {
        $userId = $this->getPayload()->getSub();
        $this->service->setUserId($userId);
    }
}
