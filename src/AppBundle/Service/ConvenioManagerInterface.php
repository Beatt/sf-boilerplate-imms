<?php

namespace AppBundle\Service;

use AppBundle\Entity\Convenio;

interface ConvenioManagerInterface
{
    public function processDataCSV($dataCSV);
}
