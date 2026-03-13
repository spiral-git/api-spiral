<?php

namespace App\Domain\Entity;

class ProductoBasicoEntity
{
    public int $Id;
    public string $SkuProducto;
    public int $Precio;
    public int $Descuento;
    public int $IdTipoDescuento;



    public function __construct(){}
}
 