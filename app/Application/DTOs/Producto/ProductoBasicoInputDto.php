<?php

namespace App\Application\DTOs\Producto;

class ProductoBasicoInputDto
{
    public int $IdTipoProducto;
    public int $IdTipoPago;
    public int $IdLenguaje;
    public int $IdTipoSetup;
    public string $Nombre;
    public string $Descripcion;
    public int $MaximoRecursos;
    public $Categorias = [];
    public $Paises = [];
    public $Imagenes = [];
    public int $Precio;
    public int $Descuento;
    public int $Amount;
    public function __construct() {}
}
