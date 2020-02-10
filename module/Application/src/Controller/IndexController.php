<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Application\Service\TagService;

/**
 * Class IndexController
 * @package Application\Controller
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
