<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\DetallePlanEntity;
use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\ProductoPlanEntity;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\SkuProductoEntity;

class GetProductoPlanOutputDtoBase
{
        public SkuProductoEntity $Sku;

    public ProductoSetupEntity $Setup;


    public ProductoPlanEntity $ProductoPlan;

    /**
     * @var DetallePlanEntity
     */
    public array $DetallesPlan = [];


    /**
     * @var PaisProductoEntity
     */
    public array $Paises = [];

     public function __construct() {}
}
