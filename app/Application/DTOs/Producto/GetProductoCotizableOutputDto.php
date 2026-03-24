<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\ProductoEntity;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\SkuProductoEntity;

class GetProductoCotizableOutputDto
{
    public SkuProductoEntity $Sku;

    public ProductoSetupEntity $Setup;

    public ProductoEntity $Producto;

    /**
     * @var ImagenProductoEntity[]
     */
    public $Imagenes = [];

    /**
     * @var CategoriaProductoEntity[]
     */
    public $Categorias = [];

    /**
     * @var PaisProductoEntity[]
     */
    public  $Paises = [];

    public function __construct() {}
}
