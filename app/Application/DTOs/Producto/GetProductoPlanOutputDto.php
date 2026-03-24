<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\ProductoEntity;

class GetProductoPlanOutputDto
{
    /**
     * @var GetProductoPlanOutputDtoBase[]
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
}
