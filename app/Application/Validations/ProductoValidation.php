<?php

namespace App\Application\Validations;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Domain\Entity\RespuestaEntity;

class ProductoValidation
{

    public static function validar(ProductoInputDto $dto): RespuestaEntity
    {
        $errores = [];

        self::validarIdTipoProducto($dto->IdTipoProducto, $errores);
        self::validarIdTipoPago($dto->IdTipoPago, $errores);
        self::validarIdLenguaje($dto->IdLenguaje, $errores);
        self::validarNombre($dto->Nombre, $errores);
        self::validarDescripcion($dto->Descripcion, $errores);


        return new RespuestaEntity(
            empty($errores) ? "Validación correcta" : "Errores de validación",
            empty($errores),
            $errores
        );
    }


    private static function validarIdTipoProducto(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idTipoProducto"] = "El tipo de producto es obligatorio";
        }
    }

    private static function validarIdTipoPago(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idTipoPago"] = "El tipo de pago es obligatorio";
        }
    }

  
    private static function validarIdLenguaje(?int $valor, array &$errores): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idLenguaje"] = "El lenguaje es obligatorio";
        }
    }

    private static function validarNombre(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["nombre"] = "El nombre es obligatorio";
            return;
        }

        if (strlen($valor) > 150) {
            $errores["nombre"] = "El nombre no puede superar 150 caracteres";
        }
    }

    private static function validarDescripcion(?string $valor, array &$errores): void
    {
        if (empty($valor)) {
            $errores["descripcion"] = "La descripción es obligatoria";
            return;
        }

        if (strlen($valor) > 1000) {
            $errores["descripcion"] = "La descripción no puede superar 1000 caracteres";
        }
    }
}
