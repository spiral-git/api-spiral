<?php

namespace App\Domain\Entity;

class CategoriaProductoEntity
{
    public int $Id;
    public int $IdProducto;
    public int $IdCategoria;

    public function __construct(){}
}
