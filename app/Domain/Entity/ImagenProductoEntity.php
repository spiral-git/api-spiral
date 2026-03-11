<?php

namespace App\Domain\Entity;

class ImagenProductoEntity
{
    public int $Id;
    public int $IdProducto;
    public string $Ruta;
    public bool $Status;

    public function __construct(){}
}
