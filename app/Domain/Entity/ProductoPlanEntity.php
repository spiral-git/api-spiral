<?php

namespace App\Domain\Entity;

class ProductoPlanEntity
{
    public int $Id;
    public string $SkuProducto;
    public string $Nombre;
    public string $Descripcion;
    public string $Etiqueta;
    public int $Precio;
    public int $Descuento;

    // public SkuProductoEntity $SkuProductoEntity;
    // public array $ListDetalles = [];

    public function __construct(){}

    
}
