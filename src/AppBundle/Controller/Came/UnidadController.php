<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Unidad;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UnidadController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/came/api/unidad", methods={"GET"}, name="came.unidad.index")
     */
    public function indexAction(Request $request)
    {
        $delegacion = $this->getUserDelegacionId();
        $unidad = $this->getUserUnidadId();
        if(is_null($delegacion) && is_null($unidad)){
            throw $this->createAccessDeniedException();
        }
        $unidades =
          $delegacion && $this->isUserDelegacionActivated()
            ? $this->getDoctrine()
              ->getRepository(Unidad::class)
              ->getAllUnidadesByDelegacion($delegacion, false)
            : [$unidad];
        return $this->jsonResponse([
            'object' => $this->get('serializer')->normalize($unidades, 'json',
                ['attributes' => [ 'id', 'nombre', 'claveUnidad']
            ])]);
    }
}