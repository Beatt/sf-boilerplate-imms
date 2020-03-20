<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="permission")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PermissionRepository")
 */
class Permission
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * @var User[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="permissions")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role", inversedBy="permissions")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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
        $this->users = $user;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return Permission
     */
    public function addUser(User $user)
    {
        if(!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @param Role $role
     * @return Permission
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    public function __toString()
    {
        return $this->name;
    }
}
