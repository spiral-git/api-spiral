<?php

namespace App\Domain\Entity;

class PaisProductoEntity
{
    public int $Id;
    public string $SkuProducto;
    public int $IdPais;

    public function __construct(){}
}
