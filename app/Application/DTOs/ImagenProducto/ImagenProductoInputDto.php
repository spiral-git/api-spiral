<?php

namespace App\Application\DTOs\ImagenProducto;

class ImagenProductoInputDto
{
    public int $IdProducto;
    /** @var string[] */
    public array $Imagenes;
    public function __construct()
    {

    }
}
