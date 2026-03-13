<?php

namespace App\Application\DTOs\PaisProducto;

class PaisProductoInputDto
{
    public string $SkuProducto;
    /** @var int[] */
    public array $Paises;
    public function __construct()
    {
    }
}
