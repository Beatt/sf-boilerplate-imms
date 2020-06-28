<?php

namespace AppBundle\ObjectValues;

final class InstitucionId
{
    private $id;

    private function __construct($id)
    {
        $this->id = $id;
    }

    public static function fromString($id)
    {
        return new self($id);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
