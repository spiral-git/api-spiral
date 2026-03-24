<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\ProductoEntity;

class GetProductoVarianteOutputDto
{
    /**
     * @var GetProductoVarianteOutputBase[]
     */
    public $Variantes;

    /**
     * @var ImagenProductoEntity[]
     */
    public $Imagenes = [];

    /**
     * @var CategoriaProductoEntity[]
     */
    public $Categorias = [];

    public ProductoEntity $Producto;

    public function __construct() {}
}
