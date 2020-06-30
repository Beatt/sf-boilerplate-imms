<?php


namespace AppBundle\Controller\Came;

use AppBundle\Entity\Institucion;
use AppBundle\Entity\Usuario;
use AppBundle\Form\Type\InstitucionType;
use AppBundle\Service\InstitucionManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InstitucionController extends \AppBundle\Controller\DIEControllerController
{
    /**
     * @Route("/came/api/institucion/{id}", methods={"POST"}, name="came.institucion.update")
     * @param Request $request
     * @param InstitucionManagerInterface $institucionManager
     * @param $id
     **/
    public function updateAction(Request $request, InstitucionManagerInterface $institucionManager, $id){
        $institucion = $this->getDoctrine()
            ->getRepository(Institucion::class)
            ->find($id);
        if (!$institucion) {
            return $this->httpErrorResponse('Not Found', Response::HTTP_NOT_FOUND);
        }
        $correo_anterior = $institucion->getCorreo();
        $form = $this->createForm(InstitucionType::class, $institucion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $institucionManager->Create($form->getData());
                $result = [
                    'status' => true
                ];
                $institucion = $this->getDoctrine()
                    ->getRepository(Institucion::class)
                    ->find($id);
                if($institucion->getCorreo() !== $correo_anterior){
                        $usuario = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(['correo' => $correo_anterior]);
                        if($usuario){
                            $usuario->setActivo(false);
                            $this->getDoctrine()->getManager()->persist($usuario);
                        }
                        $institucion->setUsuario(null);
                        $this->getDoctrine()->getManager()->persist($institucion);
                        $this->getDoctrine()->getManager()->flush();
                }
                $encoders = [new JsonEncoder()];
                $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
                $serializer = new Serializer($normalizers, $encoders);
                $result['object'] = $serializer->normalize($institucion, 'json', ['attributes' =>[
                    'id', 'nombre', 'rfc', 'direccion', 'telefono', 'correo', 'sitioWeb', 'fax', 'representante'
                ]]);
                $result['message'] = 'Los datos de la Institución han sido actualizados con éxito.';
                return $this->jsonResponse($result);
            }catch (\Exception $ex){
                return $this->failedResponse($ex->getMessage());
            }
        }
        return $this->jsonErrorResponse($form, ['message' => 'Se presentó un problema al actualizar la institución']);
    }
}