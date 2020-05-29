<?php

namespace AppBundle\Service;

use AppBundle\Util\StringUtil;

class GeneradorReferenciaBancaria implements GeneradorReferenciaBancariaInterface
{
    public function getReferenciaBancaria()
    {
        return StringUtil::generateRandomString();
    }
}
