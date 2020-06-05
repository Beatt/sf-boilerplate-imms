<?php


namespace AppBundle\Controller\Came;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CameController extends Controller
{

    public function menuAction()
    {
        $user = $this->getUser();
        $delegacion_came = $this->container->get('session')->get('user_delegacion');
        return $this->render('came/menu.html.twig', [
            'usuario' => $user, 'delegacion_came' => $delegacion_came
        ]);
    }

    /**
     * @Route("/came/usuario/delegacion", methods={"POST"}, name="came.usuario.delegacion")
     * @param Request $request
     */
    public function setDelegacionAction(Request $request)
    {
        $delegacion_id = $request->request->get('delegacion_came');
        $this->container->get('session')->set('user_delegacion', $delegacion_id);
        return $this->redirectToRoute('solicitud.index');
    }
}