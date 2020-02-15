<?php

namespace Tags\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Http\Request;

use Application\Controller\Traits\ControllerHelper;
use Tags\Service\TagService;

/**
 * Class IndexController
 * @package Tags\Controller
 */
class IndexController extends AbstractActionController
{
    use ControllerHelper;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {   
        /** @var TagService $tagService */
        $tagService = $this->getServiceManager()->get(TagService::class);
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
            $tagService = $this->getServiceManager()->get(TagService::class);
            $status = $tagService->saveTag($data);
        }

        $data = [
            "status" => $status
        ];
        return new JsonModel($data);
    }
}
