<?php


namespace AppBundle\Service;


use AppBundle\Entity\CampoClinico;

interface CampoClinicoManagerInterface
{
    public function create(CampoClinico $campoClinico);
}