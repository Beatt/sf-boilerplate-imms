<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class UserController extends BaseAdminController
{
    /**
     * @param  Usuario $user
     */
    protected function prePersistEntity($user)
    {
        $encodedPassword = $this->encodePassword($user, $user->getPlainPassword());
        $user->setContrasena($encodedPassword);
    }

    /**
     * @param  Usuario $user
     */
    protected function preUpdateEntity($user)
    {
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
}
