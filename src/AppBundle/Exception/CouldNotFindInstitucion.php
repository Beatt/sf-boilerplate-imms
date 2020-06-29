<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CouldNotFindInstitucion extends NotFoundHttpException
{
    public static function withId($id)
    {
        return new self(sprintf('La institucion con id [%s] no existe.', $id));
    }
}
