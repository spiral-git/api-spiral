<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\ProductoPlanEntity;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\SkuProductoEntity;

class ProductoPlanOutputDto
{
    public SkuProductoEntity $Sku;
    public ProductoSetupEntity $Setup;
    public ProductoPlanEntity $ProductoPlan;
    public function __construct()
    {
        //
    }
}
