<?php

namespace App\Domain\Entity;

class ProductoVarianteEntity
{
    public int $Id;
    public string $SkuProducto;
    public string $Nombre;
    public int $Precio;
    public int $Descuento;
    public int $IdTipoDescuento;

    public function __construct(){}
    
}
