<?php

namespace App\Application\DTOs\Producto;

class ProductoVarianteInputDto
{
    public int $IdTipoProducto;
    public int $IdTipoPago;
    public int $IdLenguaje;
    public string $Nombre;
    public string $Descripcion;
    public int $MaximoRecursos;
    public $Categorias = [];
    public $Paises = [];
    public $Imagenes = [];
    public $Variantes = [];

    public function __construct() {}
}
