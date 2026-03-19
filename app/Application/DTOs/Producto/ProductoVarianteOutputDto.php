<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\SkuProductoEntity;

class ProductoVarianteOutputDto
{
    public SkuProductoEntity $Sku;
    public ProductoSetupEntity $Setup;
    public ProductoVarianteEntity $ProductoVariante;

    public function __construct()
    {
        
    }
}
 