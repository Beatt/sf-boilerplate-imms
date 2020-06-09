<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;

interface CampoClinicoManagerInterface
{
    public function create(CampoClinico $campoClinico);
    public function delete(CampoClinico $campoClinico);
}