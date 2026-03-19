<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\SkuProductoEntity;

class ProductoCotizableOutputDto
{
    public SkuProductoEntity $Sku;
    public ProductoSetupEntity $Setup;

    public function __construct()
    {
        
    }
}
