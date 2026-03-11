<?php

namespace App\Application\Validations;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Domain\Entity\RespuestaEntity;

class ImagenProductoValidation
{
    public static function validar(ImagenProductoInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores);
        self::validarRutaImagen($dto->Ruta, $errores);

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

    private static function validarRutaImagen(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["ruta"] = "La ruta de la imagen no es valida";
        }
    }
}
