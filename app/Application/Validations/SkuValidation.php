<?php

namespace App\Application\Validations;

use App\Application\DTOs\Sku\SkuInputDto;
use App\Domain\Entity\RespuestaEntity;

class SkuValidation
{
    public static function validar(SkuInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores);
        self::validarIdSetup($dto->IdSetup, $errores);

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

    private static function validarIdSetup(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idSetup"] = "El id del setup es obligatorio";
        }
    }

}
