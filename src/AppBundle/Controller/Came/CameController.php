<?php


namespace AppBundle\Controller\Came;


use AppBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CameController extends Controller
{

    public function menuAction()
    {
        /** @var Usuario $user */
        $user = $this->getUser();
        $delegacion_came = $this->container->get('session')->get('user_delegacion');
        $unidad_came = $this->container->get('session')->get('user_unidad');
        return $this->render('came/menu.html.twig', [
            'usuario' => $user,
            'delegacion_came' => $delegacion_came,
            'unidad_came' => $unidad_came,
        ]);
    }

    /**
     * @Route("/came/usuario/delegacion_unidad", methods={"POST"}, name="came.usuario.delegacion_unidad")
     * @param Request $request
     */
    public function setDelegacionAction(Request $request)
    {
        $delegacion_unidad_id = $request->request->get('delegacion_unidad_came');
        $splitDelUnid = explode("_", $delegacion_unidad_id);
        if (count($splitDelUnid) != 2 || !( in_array($splitDelUnid[0], ['D', 'U'] )))
          return $this->redirectToRoute('came.solicitud.index');

        /** @var Session $session */
        $session = $this->container->get('session');
        $delegacion_id = null;
        $unidad_id = null;
        if ($splitDelUnid[0] == "D") {
          $delegacion_id = $splitDelUnid[1];
          $session->remove('user_unidad');
          $session->set('user_delegacion', $delegacion_id);
        } else { // $splitDelUnid[1] == 'U'
          $unidad_id = $splitDelUnid[1];
          $session->remove('user_delegacion');
          $session->set('user_unidad', $unidad_id);
        }
        return $this->redirectToRoute('came.solicitud.index');
    }
}