<?php

namespace App\Application\Mappers;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Domain\Entity\ProductoEntity;
use Carbon\Carbon;

class MapperProductos
{

    public static function inputDtoToEntity(ProductoInputDto $dto): ProductoEntity
    {
        $producto = new ProductoEntity();
        $producto->IdTipoProducto = $dto->IdTipoProducto;
        $producto->IdTipoPago = $dto->IdTipoPago;
        $producto->IdLenguaje = $dto->IdLenguaje;
        $producto->Nombre = strtoupper($dto->Nombre);
        $producto->Descripcion = $dto->Descripcion;
        $producto->CreateAt = Carbon::now();
        $producto->ValoracionGeneral = 0; 
        $producto->Status = "activo";
        return $producto;
    }

    
}
