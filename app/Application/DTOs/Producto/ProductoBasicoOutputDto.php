<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\ProductoBasicoEntity;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\SkuProductoEntity;

class ProductoBasicoOutputDto
{
    public SkuProductoEntity $Sku;
    public ProductoSetupEntity $Setup;
    public ProductoBasicoEntity $ProductoBasico;

    public function __construct()
    {
        
    }
}
