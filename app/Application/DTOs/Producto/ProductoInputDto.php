<?php

namespace App\Application\DTOs\Producto;

class ProductoInputDto
{
    public int $IdTipoProducto;
    public int $IdTipoPago;
    public int $IdLenguaje;
    public string $Nombre;
    public string $Descripcion;

    public function __construct(){}
}
