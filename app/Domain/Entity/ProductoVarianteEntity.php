<?php

namespace App\Domain\Entity;

class ProductoVarianteEntity
{
    public int $Id;
    public string $SkuProducto;
    public string $Nombre;
    public int $Precio;
    public int $Descuento;

    public SkuProductoEntity $SkuProductoEntity;

    public function __construct(){}
    
}
