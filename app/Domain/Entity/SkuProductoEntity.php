<?php

namespace App\Domain\Entity;

class SkuProductoEntity
{
    public string $Sku;
    public int $IdProducto;
    public string $Status;
    public int $MaximoRecursos;
    public int $IdSetupProducto;

    public function __construct(){}
    
}
