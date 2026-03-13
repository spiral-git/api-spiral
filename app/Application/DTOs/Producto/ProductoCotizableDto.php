<?php

namespace App\Application\DTOs\Producto;

class ProductoCotizableDto
{
    public int $IdProducto;
    public int $MaximoRecursos;
    public int $IdTipoSetup;
    public int $AmountSetup;
    public function __construct()
    {
        
    }
}
