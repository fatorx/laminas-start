<?php 

namespace Users\Service;

use Doctrine\ORM\EntityManager;
use Laminas\Hydrator\ClassMethods;
use Laminas\Filter\FilterChain;
use Cocur\Slugify\Slugify;

use Application\Service\BaseService;
use Users\Entity\User;

/**
 * Class TokenService
 * @package Users\Service
 */
class TokenService extends BaseService
{
    const EXPIRATION_MINUTS = 15;
    const AUDIENCE = 'app';

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
     * @param array $pars 
     * @return User|false
     */
    public function checkUser(array $pars)
    {
        $user = $this->em->getRepository($this->entity)
                         ->findOneBy(['email' => $pars['username']]); 
        if ($user) {       
            $passwordHash = $user->getPassword(); 
            return (password_verify($pars['password'], $passwordHash)) ? $user : false;
        }
        return false;
    }
 
    /**
     * @return int
     */
    public function getExpiration()
    {
        $dateTime = new \Datetime();
        $dateTime->modify('+' . self::EXPIRATION_MINUTS . ' minutes');
        return $dateTime->getTimestamp();
    }

    /**
     * @return int
     */
    public function getIssuedAt()
    {
        $dateTime = new \Datetime();
        return $dateTime->getTimestamp();
    }

    /**
     * @return string
     */
    public function getAudience()
    {
        return self::AUDIENCE;
    }
}