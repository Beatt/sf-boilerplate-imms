<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $roles = $this->getRoles($user->getRols());
        if(in_array('SUPER', $roles)){
            return $this->redirectToRoute('admin');
        }else if(in_array('CAME', $roles)){
            return $this->redirectToRoute('solicitud.index');
        }else if(in_array('IE', $roles)){
            return $this->redirectToRoute('solicitudes#index');
        }else if(in_array('FOFOE', $roles)){

        }
    }

    private function getRoles($roles)
    {
        $result = [];
        foreach ($roles as $role) {
            $result[] = $role->getClave();
        }
        return $result;
    }
}
