<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Solicitud;
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
        $status = 200;
        if(isset($data['status'])){
            $json['status'] = $data['status'];
            if(!$data['status']){
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
        }else{
            $json['status'] = true;
        }
        if(isset($data['message'])){
            $json['message'] = $data['message'];
        }else if(isset($data['status']) && $json['status']){
            $json['message'] = 'Solicitud procesada con éxito';
        }
        if (isset($result['error'])) {
            $json['error'] = $result['error'];
        }
        if (isset($data['object'])) {
            $json['data'] = $data['object'];
        }
        if(isset($data['meta'])){
            $json['meta'] = $data['meta'];
        }
        return new JsonResponse($json, $status);

    }

    protected function jsonErrorResponse($form, $data = [])
    {
        return new JsonResponse([
            'message' => isset($data['message']) ? $data['message'] : 'Ocurrío un error al procesar su solicitud',
            'status' => false,
            'errors' => $this->get('serializer')->normalize($this->getFormErrors($form), 'json'),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function successResponse($message)
    {
        return new JsonResponse([
            'message' => $message,
            'status' => true,
        ], Response::HTTP_OK);
    }

    protected function failedResponse($message)
    {
        return new JsonResponse([
            'message' => $message,
            'status' => true,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function getUserDelegacionId($query_delegacion)
    {
        $result = null;
        $user = $this->getUser();
        if($user){
            if(!$query_delegacion){
                $del_object = $user->getDelegaciones()->first();
                $result = $del_object ? $del_object->getId() : $result;
            }else{
                if($user->getDelegaciones()->exists(function($key, $value)  use ($query_delegacion){
                    return $value->getId() === $query_delegacion;
                })){
                    $result = $query_delegacion;
                };
            }
        }
        return $result;
    }

    protected function validarSolicitudDelegacion(Solicitud $solicitud)
    {
        $result = true;
        $delegacion = $solicitud->getDelegacion();
        if($delegacion){
            $user = $this->getUser();
            $result = $user->getDelegaciones()->exists(function($key, $value)  use ($delegacion){
                return $value->getId() === $delegacion->getId();
            });
        }
        return $result;
    }
}
