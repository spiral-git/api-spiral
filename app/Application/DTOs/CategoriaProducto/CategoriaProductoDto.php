<?php

namespace App\Application\DTOs\CategoriaProducto;

class CategoriaProductoDto
{
    public int $IdProducto;
    /** @var int[] */
    public array $Categorias;
    public function __construct()
    {
        
    }
}
