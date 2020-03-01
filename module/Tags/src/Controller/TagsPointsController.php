<?php

namespace Tags\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Request;

use Application\Controller\ApiController;
use Tags\Service\TagService;

/**
 * Class TagsPointsController
 * @package Tags\Controller
 */
class TagsPointsController extends ApiController
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
        $userId = $this->getPayload()->getSub();
        $this->service->setUserId($userId);
    }

    /**
     * @return JsonModel
     */
    public function create($data)
    {   
        $this->preLoadMethod();
        $status = $this->service->createOrUpdatePoints($data);
        $id     = $this->service->getId();
        if (!$status) {
            $this->httpStatusCode = 400;
        }
        $data = [
            'id'     => $id,
        ];
        return $this->createResponse($data);
    }
}
