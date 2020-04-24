<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class DIEControllerController extends Controller
{
    protected function getFormErrors(Form $form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child/** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }

    protected function jsonResponse($data)
    {
        $result = $data;
        $json = new JsonResponse([
            'message' => $result['status'] ?
                "¡La información se actualizado correctamente!" :
                '¡Ha ocurrido un problema, intenta más tarde!',
            'status' => $result['status'] ?
                Response::HTTP_OK :
                Response::HTTP_UNPROCESSABLE_ENTITY
        ]);
        if(isset($result['error'])){
            $json['error'] = $result['error'];
        }
    }

    protected function jsonErrorResponse($form)
    {
        return new JsonResponse([
            'message' =>    '¡Ha ocurrido un problema, intenta más tarde!',
            'status' => false,
            'errors' => $this->get('serializer')->normalize($this->getFormErrors($form), 'json'),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
