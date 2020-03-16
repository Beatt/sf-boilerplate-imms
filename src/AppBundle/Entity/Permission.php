<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permission
 *
 * @ORM\Table(name="permission")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PermissionRepository")
 */
class Permission
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="security_role", type="string", length=150)
     */
    private $securityRole;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="permissions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Permission
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $securityRole
     * @return Permission
     */
    public function setSecurityRole($securityRole)
    {
        $this->securityRole = $securityRole;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityRole()
    {
        return $this->securityRole;
    }

    /**
     * @param User $user
     * @return Permission
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->name;
    }
}
