<?php

namespace App\Application\Validations;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Domain\Entity\RespuestaEntity;

class CategoriaProductoValidation
{
    public static function validar(CategoriaProductoDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores);
        self::validarIdCategoria($dto->IdCategoria, $errores);

        return new RespuestaEntity(
            empty($errores) ? "Validación correcta" : "Errores de validación",
            empty($errores),
            $errores
        );
    }

    private static function validarIdProducto(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idProducto"] = "El id del producto es obligatorio";
        }
    }

    private static function validarIdCategoria(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idCategoria"] = "El id de la categoria es obligatorio";
        }
    }

    
}
