<?php

declare(strict_types=1);

namespace Tags\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Request;

use Tags\Service\TagService;

/**
 * Class IndexController
 * @package Tags\Controller
 */
class IndexController extends AbstractActionController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {   
        /** @var TagService $tagService */
        $tagService = $this->getServiceMager()->get(TagService::class);
        $list = $tagService->getListTags();

        $data = [
            "list" => $list
        ];
        return new ViewModel($data);
    }

    /**
     * @return JsonModel
     */
    public function registerAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();

        $status = false;
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            /** @var TagService $tagService */
            $tagService = $this->getServiceMager()->get(TagService::class);
            $status = $tagService->saveTag($data);
        }

        $data = [
            "status" => $status
        ];
        return new JsonModel($data);
    }

    /**
     * @return ServiceManager
     */
    public function getServiceMager()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

}
