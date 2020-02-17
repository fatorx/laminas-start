<?php 

namespace Tags\Service;

use Doctrine\ORM\EntityManager;
use Application\Service\BaseService;
use Laminas\Hydrator\ClassMethods;
use Laminas\Filter\FilterChain;
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

    /**
     * @var Tag
     */
    private $entity;

    /**
     * TagService constructor.
     */
    public function __construct() 
    {
        $this->entity = Tag::class;
    }

    /**
     * @param int $id 
     * @return array
     */
    public function getItem(int $id) : array
    {
        $tag = $this->em->getRepository($this->entity)
                        ->findOneBy(['id' => $id, 'userId' => $this->userId]); 
        if ($tag) {
            return $tag->toArray();
        }
        return [];
    }

    /**
     * @param int $limit = 10 
     * @return array
     */
    public function getList(int $limit = 10) : array
    {
        $tags = $this->em->getRepository($this->entity)
                         ->findBy(['userId' => $this->userId]); 
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
     * @return array
     */
    public function getListTags() : array
    {
        return $this->executeSql("SELECT * FROM tags LIMIT 10", "all");
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function create(array $pars) : bool
    {
        $pars['name'] = $this->filterName($pars['name']);

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
    public function update(int $id, array $pars) : bool
    {
        $entityRef = $this->em->getReference($this->entity, $id);
        
        $pars['name'] = $this->filterName($pars['name']);

        $slugify = new Slugify();
        $pars['slug'] = $slugify->slugify($pars['name']);

        $hydrator = new ClassMethods();
        $hydrator->hydrate($pars, $entityRef);
        
        $entityRef->setUpdatedAt();
        $this->em->persist($entityRef);
        $this->em->flush();
        
        return $entityRef->toArray();
    }

    /**
     * @param string
     * @return 
     */
    public function filterName(string $name) : string
    {
        $filter = new FilterChain();
        
        $filter->attachByName('StringTrim');
        $filter->attachByName('StripNewlines');
        $filter->attachByName('StripTags');
        
        return $filter->filter($name);
    }

    /**
     * @param int $id
     */
    public function delete(int $id) : Tag
    {
        $entity = $this->em->getRepository($this->entity)
                           ->findOneBy(['id' => $id, 'userId' => $this->userId]);
        
        if($entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }  
        return $entity;          
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}    