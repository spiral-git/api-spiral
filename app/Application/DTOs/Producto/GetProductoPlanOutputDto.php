<?php

namespace App\Application\DTOs\Producto;

use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\ProductoEntity;

class GetProductoPlanOutputDto
{
    /**
     * @var GetProductoPlanOutputDtoBase
     */
    public array $Variantes;

    /**
     * @var ImagenProductoEntity
     */
    public array $Imagenes = [];

    /**
     * @var CategoriaProductoEntity
     */
    public array $Categorias = [];

    public ProductoEntity $Producto;
}
