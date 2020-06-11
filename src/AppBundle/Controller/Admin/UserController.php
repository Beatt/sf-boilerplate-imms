<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Permiso;
use AppBundle\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class UserController extends BaseAdminController
{
    /**
     * @param  Usuario $user
     */
    protected function prePersistEntity($user)
    {
        $this->setPermisosToUser($user);

        $encodedPassword = $this->encodePassword($user, $user->getPlainPassword());
        $user->setContrasena($encodedPassword);
    }

    /**
     * @param  Usuario $user
     */
    protected function preUpdateEntity($user)
    {
        $this->removePermisosFromUser($user);
        $this->setPermisosToUser($user);

        if (!$user->getPlainPassword()) {
            return;
        }

        $encodedPassword = $this->encodePassword($user, $user->getPlainPassword());
        $user->setContrasena($encodedPassword);
    }

    /**
     * @param  Usuario $user
     * @return string
     */
    private function encodePassword($user, $password)
    {
        $passwordEncoderFactory = $this->get('security.encoder_factory');
        $encoder = $passwordEncoderFactory->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

    /**
     * @param Usuario $user
     */
    protected function setPermisosToUser(Usuario $user)
    {
        $permisosByRol = $this
            ->getDoctrine()
            ->getRepository(Permiso::class)
            ->findBy(['rol' => $user->getRol()]);

        /** @var Permiso $permiso */
        foreach ($permisosByRol as $permiso) {
            $user->addPermiso($permiso);
        }
    }

    /**
     * @param Usuario $entity
     * @param array $entityProperties
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function createEditForm($entity, array $entityProperties)
    {
        if(!$entity->getPermisos()->isEmpty()) {
            $rol = $entity->getPermisos()->first()->getRol();
            $entity->setRol($rol);
        }

        return parent::createEditForm($entity, $entityProperties);
    }

    /**
     * @param Usuario $user
     */
    protected function removePermisosFromUser(Usuario $user)
    {
        if (!$user->getPermisos()->isEmpty()) {
            foreach ($user->getPermisos() as $permiso) $user->removePermiso($permiso);
        }
    }
}
