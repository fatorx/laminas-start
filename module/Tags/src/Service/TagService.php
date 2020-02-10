<?php declare(strict_types=1);

namespace Tags\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\BaseService;

use Tags\Entity\Tag;

/**
 * Class TagService
 * @package Tags\Service
 */
class TagService extends BaseService
{
    /**
     * TagService constructor.
     */
    public function __construct() 
    {

    }

    /**
     * @return array
     */
    public function getListTags()
    {
        return $this->executeSql("SELECT * FROM tags LIMIT 10", "all");
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function saveTag(array $pars)
    {
        $tag = new Tag($pars);
        $this->em->persist($tag);
        $this->em->flush();
        
        return $tag->getId() > 0;
    }
}    