<?php

namespace App\Application\Validations;

use App\Application\DTOs\Setup\SetupInputDto;
use App\Domain\Entity\RespuestaEntity;

class SetupValidation
{
    public static function validar(SetupInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdTipoSetup($dto->IdTipoSetup, $errores);

        return new RespuestaEntity(
            empty($errores) ? "Validación correcta" : "Errores de validación",
            empty($errores),
            $errores
        );
    }

    private static function validarIdTipoSetup(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idProducto"] = "El id del tipo de setup del producto es obligatorio";
        }
    }

  
}
