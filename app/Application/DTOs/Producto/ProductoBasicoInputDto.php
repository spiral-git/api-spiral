<?php

namespace App\Application\DTOs\Producto;

class ProductoBasicoInputDto
{

    public int $IdProducto;
    public int $MaximoRecursos;
    public int $IdTipoSetup;
    public int $AmountSetup;
    public int $Precio;
    public int $Descuento;
    public int $IdTipoDescuento;
    public function __construct()
    {

    }
}
