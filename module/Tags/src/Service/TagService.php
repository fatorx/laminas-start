<?php 

namespace Tags\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\BaseService;
use Laminas\Hydrator\ClassMethods;
use Cocur\Slugify\Slugify;

use Tags\Entity\Tag;

/**
 * Class TagService
 * @package Tags\Service
 */
class TagService extends BaseService
{
    /**
     * @var int
     */
    private $id = 0;

    private $entity;

    /**
     * TagService constructor.
     */
    public function __construct() 
    {
        $this->entity = Tag::class;
    }

    /**
     * @return array
     */
    public function getListTags()
    {
        return $this->executeSql("SELECT * FROM tags LIMIT 10", "all");
    }

    /**
     * @param int $id 
     * @return Tag
     */
    public function getItem(int $id)
    {
        $tag = $this->em->getRepository($this->entity)->find($id); 
        if ($tag) {
            return $tag->toArray();
        }
        return [];
    }

    /**
     * @param int $limit = 10 
     * @return array
     */
    public function getList(int $limit = 10)
    {
        $tags = $this->em->getRepository($this->entity)->findAll(); 
        if ($tags) {
            $list = [];
            foreach ($tags as $tag) {
                $list[] = $tag->toArray();
            }
            return $list;
        }
        return [];
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function create(array $pars) : bool
    {
        $slugify = new Slugify();
        $pars['slug'] = $slugify->slugify($pars['name']);

        $tag = new Tag($pars);
        $this->em->persist($tag);
        $this->em->flush();
        
        $this->id = $tag->getId();
        return $this->id > 0;
    }

    /**
     * @param int $id
     * @param array $pars
     * @return bool
     */
    public function update(int $id, array $pars)
    {
        $entityRef = $this->em->getReference($this->entity, $id);
        
        $slugify = new Slugify();
        $pars['slug'] = $slugify->slugify($pars['name']);
        
        $hydrator = new ClassMethods();
        $hydrator->hydrate($pars, $entityRef);
        
        $entityRef->setUpdatedAt();
        $this->em->persist($entityRef);
        $this->em->flush();
        
        return $entityRef->toArray();
    }

    public function delete($id)
    {
        $entity = $this->em->getRepository($this->entity)->find($id);
        
        if($entity) {
            $this->em->remove($entity);
            $this->em->flush();
            return $id;
        }             
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}    