<?php

namespace App\Application\Validations;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Domain\Entity\RespuestaEntity;

class PaisProductoValidation
{
    public static function validar(PaisProductoInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdPais($dto->IdPais, $errores);
        self::validarSku($dto->SkuProducto, $errores);

        return new RespuestaEntity(
            empty($errores) ? "Validación correcta" : "Errores de validación",
            empty($errores),
            $errores
        );
    }


    private static function validarIdPais(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idPais"] = "El id del pais es obligatorio";
        }
    }

    private static function validarSku(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["sku"] = "El sku es obligatorio";
        }
    }
}
