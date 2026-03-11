<?php

namespace App\Domain\Entity;

class ProductoBasicoEntity
{
    public int $Id;
    public string $SkuProducto;
    public int $Precio;
    public int $Descuento;

    public SkuProductoEntity $SkuProductoEntity;

    public function __construct(){}
}
