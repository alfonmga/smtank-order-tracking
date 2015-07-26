<?php

namespace OrderTracking\BackendBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Uecode\Bundle\ApiKeyBundle\Entity\ApiKeyUser as ApiKeyUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends ApiKeyUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}