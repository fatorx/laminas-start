<?php 

namespace Users\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Hydrator\ClassMethods;
use Laminas\Filter\FilterChain;
use Cocur\Slugify\Slugify;

use Application\Service\BaseService;
use Users\Entity\User;

/**
 * Class UserService
 * @package Tags\Service
 */
class UserService extends BaseService
{
    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var User
     */
    private $entity;

    /**
     * TagService constructor.
     */
    public function __construct() 
    {
        $this->entity = User::class;
    }

    /**
     * @param int $id 
     * @return array
     */
    public function getItem(int $id) : array
    {
        $user = $this->em->getRepository($this->entity)
                        ->findOneBy(['id' => $this->userId]); 
        if ($user) {
            return $user->toArray();
        }
        return [];
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function create(array $pars) : bool
    {
        $pars['name'] = $this->filterName($pars['name']);
        $pars['nickname'] = $this->filterName($pars['nickname']);
        
        $this->status = $this->validCreate($pars);
        if (!$this->status) {
            return false;
        }

        $pars['active'] = 1;
        $user = new $this->entity($pars);
        $this->em->persist($user);
        $this->em->flush();
        
        $this->id = $user->getId();
        return $this->id > 0;
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function validCreate(array $pars) : bool
    {
        $sqlModel  = ' SELECT t FROM '.$this->entity.' t 
                       WHERE t.email = :email ';
        $query = $this->em->createQuery($sqlModel);

        $query->setParameter('email', $pars['email']);
        $result = $query->getResult();

        return empty($result);
    }

    /**
     * @param int $id
     * @param array $pars
     * @return array
     */
    public function update(int $id, array $pars) : array
    {
        try {
            $entityRef = $this->em->find($this->entity, $id);
        } catch(\Exception $e) {
            echo $e->getMessage(); exit();
        }
        
        if (!$entityRef) {
            $this->status = false;
            return [];
        }
        $pars['name'] = $this->filterName($pars['name']);
        $pars['nickname'] = $this->filterName($pars['nickname']);
        
        $this->status = $this->validUpdate($id, $pars);
        if (!$this->status) {
            return [];
        }

        $hydrator = new ClassMethods();
        $hydrator->hydrate($pars, $entityRef);
        
        $entityRef->setUpdatedAt();
        $this->em->persist($entityRef);
        $this->em->flush();
        
        return $entityRef->toArray();
    }

    /**
     * @param array $pars
     * @return bool
     */
    public function validUpdate(int $id, array $pars) : bool
    {
        $sqlModel  = ' SELECT t FROM '.$this->entity.' t 
                       WHERE t.email = :email 
                       AND t.id <> :id';
        $query = $this->em->createQuery($sqlModel);

        $query->setParameter('id', $pars['id']);
        $query->setParameter('email', $pars['email']);
        $result = $query->getResult();
        
        return empty($result);
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
        $tagId = $pars['tag_id'];
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