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
        $delegacion = $this->getUserDelegacionId($request->query->get('delegacion'));
        if(is_null($delegacion)){
            throw $this->createAccessDeniedException();
        }
        $unidades =  $this->getDoctrine()
            ->getRepository(Unidad::class)
            ->getAllUnidadesByDelegacion($delegacion);
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => [ 'id', 'nombre']
            ])]);
    }
}