<?php

namespace Tags\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Request;

use Application\Controller\ApiController;
use Tags\Service\TagService;

/**
 * Class TagsController
 * @package Tags\Controller
 */
class TagsController extends ApiController
{
    /** 
     * @var TagService $service 
     */
    protected $service;

    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    public function preLoadMethod()
    {
        $userId = $this->getPayload()->getSub(); // use token payload for recover data
        $this->service->setUserId($userId);
    }

    /**
     * @return JsonModel
     */
    public function getList()
    {
        $this->preLoadMethod();        
        $list = $this->service->getList();

        $data = [
            'list'   => $list,
            'action' => 'get',
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function get($id)
    {
        $this->preLoadMethod();
        $item = $this->service->getItem((int)$id);
        if (empty($item)) {
            $this->httpStatusCode = 404;
        }
        $data = [
            'tag'    => $item,
            'method' => 'get'
        ];
        return $this->createResponse($data);
    }

    /**
     * @return JsonModel
     */
    public function create($data)
    {
        $this->preLoadMethod();
        $status = $this->service->create($data);
        $id     = $this->service->getId();

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
        $item = $this->service->update((int)$id, $data);
        if (empty($item)) {
            $this->httpStatusCode = 404;
        }

        $data = [
            'item'   => $item,
            'action' => 'update'
        ];
        return $this->createResponse($data);
    }
     
    /**
     * @return JsonModel
     */
    public function replaceList($data)
    {   
        $this->preLoadMethod();
        $data = [
            'action' => 'replaceList'
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
}
