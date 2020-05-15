<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Unidad;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnidadController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/api/came/unidad", methods={"GET"}, name="came.unidad.index")
     */
    public function indexAction(Request $request)
    {
        $user_delegacion = null;
        $unidades =  $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($user_delegacion);
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => [ 'id', 'nombre']
            ])]);
    }
}