<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\SkuProductoEntity;

class GetProductoVarianteOutputBase
{
    public SkuProductoEntity $Sku;

    public ProductoSetupEntity $Setup;


    public ProductoVarianteEntity $ProductoVariante;

    /**
     * @var PaisProductoEntity
     */
    public array $Paises = [];



    public function __construct() {}
}
