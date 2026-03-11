<?php

namespace App\Application\DTOs\Sku;

class SkuInputDto
{

    public int $IdProducto;
    public int $MaximoRecursos;
    public int $IdSetup;

    public function __construct()
    {
    }
}
