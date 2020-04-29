<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $json = [];
        if(isset($data['status'])){
            $result['status'] = $data['status'];
        }
        if(isset($data['message'])){
            $result['message'] = $data['message'];
        }
        if (isset($result['error'])) {
            $json['error'] = $result['error'];
        }
        if (isset($data['object'])) {
            $json['data'] = $data['object'];
        }
        return new JsonResponse($json);

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
