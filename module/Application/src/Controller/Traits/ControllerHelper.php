<?php

namespace Application\Controller\Traits;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Http\Request;
use Laminas\Json\Json;

/**
 * Trait ControllerHelper
 * @package Application\Traits
 */
trait ControllerHelper
{
    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        if (! $this instanceof AbstractActionController) {
            throw new \RuntimeException('This trait is only useful in controllers');
        }

        return $this->getEvent()->getApplication()->getServiceManager();
    }

    /**
     * @param string $param Parameter name to retrieve, or null to get all.
     * @param mixed $default Default value to use when the parameter is missing.
     * @return mixed
     */
    public function getJsonParameters($param = null, $default = null)
    {
        /** @var Request $request */
        $request = $this->getRequest();
        $content = $request->getContent();
        $data = Json::decode($content, 1);
        if ($param == null) {
            return $data;
        }
        return isset($data[$param]) ? $data[$param] : $default;
    }
}
