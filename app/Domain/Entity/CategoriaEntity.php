<?php

namespace App\Domain\Entity;

class CategoriaEntity
{
    public int $Id;
    public string $Nombre;
    public bool $Status;
    public int $IdLenguaje;


    public function __construct(){}
}
