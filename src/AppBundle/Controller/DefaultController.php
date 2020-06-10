<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @throws \Exception
     */
    public function indexAction()
    {
        $roles = $this->getUser()->getRoles();

        if($this->isUserWithFOFOERol($roles)) {
            return $this->redirectToRoute('fofoe/inicio');
        }

        switch($roles[0]) {
            case 'ROLE_SUPER':
                return $this->redirectToRoute('admin');
            case 'ROLE_CAME':
                return $this->redirectToRoute('solicitud.index');
            case 'ROLE_IE':
                return $this->redirectToRoute('ei#perfil', [
                    'id' => $this->getUser()->getInstitucion()->getId()
                ]);
            default:
                throw new \Exception('El usuario no tiene un rol asignado.');
        }
    }

    private function isUserWithFOFOERol(array $roles)
    {
        return in_array('ROLE_FOFOE_INICIO', $roles) ||
            in_array('ROLE_FOFEO_VALIDAR_PAGO', $roles) ||
            in_array('ROLE_FOFEO_VALIDAR_PAGO_MULTIPLE', $roles) ||
            in_array('ROLE_FOFEO_REGISTRAR_FACTURA', $roles) ||
            in_array('ROLE_FOFEO_DETALLE_INSTITUCION_EDUCATIVA', $roles);
    }
}
