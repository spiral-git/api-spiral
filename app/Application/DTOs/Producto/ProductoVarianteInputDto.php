<?php

namespace App\Application\DTOs\Producto;

class ProductoVarianteInputDto
{
    public int $IdProducto;
    public int $MaximoRecursos;
    public int $IdTipoSetup;
    public int $AmountSetup;
    public int $Precio;
    public int $Descuento;
    public int $IdTipoDescuento;
    public string $Nombre;


    public function __construct() {}
}
