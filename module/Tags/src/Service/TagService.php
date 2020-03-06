<?php 

namespace Tags\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Hydrator\ClassMethods;
use Laminas\Filter\FilterChain;
use Cocur\Slugify\Slugify;

use Application\Service\BaseService;
use Tags\Entity\Tag;
use Tags\Entity\TagPoint;

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
     * @var Tag
     */
    private $entityPoint;

    /**
     * TagService constructor.
     */
    public function __construct() 
    {
        $this->entity = Tag::class;
        $this->entityPoint = TagPoint::class;
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
     * Query builder
     * 
     * @param string $date 
     * @param string $str 
     * @param int $limit = 10 
     * @return array
     */
    public function getList(string $date, string $str, int $limit = 10) : array
    {
        $query = $this->em->getRepository($this->entity)
                          ->createQueryBuilder('t');

        $query->where('t.userId = :userId')
              ->setParameter('date', $date);

        if ($str != '' ) {
            $query->andWhere('t.name like :par')
                  ->setParameter('par', '%' . $str . '%')
            ;
        }

        $tags = $query->getQuery()->setFirstResult(0)
                                  ->setMaxResults($limit)
                                  ->getResult();

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
     * @param string $date 
     * @param string $str 
     * @param int $limit = 10 
     * @return array
     */
    public function getListDql(string $date, string $str, int $limit = 10) : array
    {
        $sqlModel   = ' SELECT t FROM '.$this->entity.' t '; 
        $sqlModel  .= ' WHERE t.id > 0 ';
        $sqlModel  .= ' AND   t.creationDate = :date ';
        $sqlModel  .= ' AND   t.userId = :user_id ';
        $sqlModel  .= ' AND t.name LIKE :name ';
        
        $query = $this->em->createQuery($sqlModel);

        $query->setParameter('date', $date);
        $query->setParameter('user_id', $this->userId);
        $query->setParameter('name', '%' . $str . '%');
        $tags = $query->setFirstResult(0)
                      ->setMaxResults($limit)
                      ->getResult();

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
     * @param string $date 
     * @param int $limit = 10 
     * @return array
     */
    public function getListRepository(string $date, string $str, int $limit = 10) : array
    {
        $dateTime = new \Datetime($date);
        $pars = ['creationDate' => $dateTime, 'userId' => $this->userId];
        $tags = $this->em->getRepository($this->entity)
                         ->findBy($pars, [], $limit); 
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
    public function getListRawQuery() : array
    {
        $sql = ' SELECT id, user_id, name, slug, creation_date, creation_time FROM tags LIMIT 10 ';
        return $this->executeSql($sql, "all");
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

        if (!$this->validCreate($pars)) {
            return false;
        }
        
        $tag = new Tag($pars);
        $this->em->persist($tag);
        $this->em->flush();
        
        $this->id = $tag->getId();
        return $this->id > 0;
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function validCreate(array $pars) : bool
    {
        $sqlModel  = ' SELECT t FROM '.$this->entity.' t 
                       WHERE t.creationDate > CURRENT_DATE()
                       AND t.slug = :slug
                       AND t.userId = :userId ';
        $query = $this->em->createQuery($sqlModel);

        $query->setParameter('userId', $this->userId);
        $query->setParameter('slug', $pars['slug']);
        $result = $query->getResult();

        return empty($result);
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

            $tagPoint = $this->em->getRepository($this->entityPoint)
                             ->findOneBy(['tagId' => $tagId]);
            $this->em->remove($tagPoint);
            
            $this->em->flush();                 
        }  
        return $entity;          
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function createOrUpdatePoints(array $pars) : bool
    {   
        $tagId = (int)$pars['tag_id'];
        $entity = $this->checkTagById($tagId);

        if (!$entity) {
            return false;
        }                

        $tagPoint = $this->em->getRepository($this->entityPoint)
                             ->findOneBy(['tagId' => $tagId]);
        
        if($tagPoint) {
            $points = $tagPoint->getPoints() + 1;
            $tagPoint->setUpdatedAt();
            $tagPoint->setPoints($points);
        }  else {
            $pars['points'] = 1;
            $tagPoint = new $this->entityPoint($pars);
        }

        $this->em->persist($tagPoint);
        $this->em->flush();

        return true;
    }

    /**
     * @param int $tagId
     * @return Tag|false
     */
    public function checkTagById(int $tagId)
    {
        $entity = $this->em->getRepository($this->entity)
                           ->findOneBy(['id' => $tagId, 'userId' => $this->userId]);
                
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