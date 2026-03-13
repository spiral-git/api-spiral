<?php

namespace App\Application\Validations;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Application\Services\LenguajeService;
use App\Application\Services\TipoPagoService;
use App\Application\Services\TipoProductoService;
use App\Domain\Entity\RespuestaEntity;

class ProductoValidation
{

    private static array $translations = [
        "es" => [
            "validation_success" => "Validación correcta",
            "validation_error" => "Errores de validación",
            "validation_tipoProducto" => "El tipo de producto es obligatorio",
            "validation_tipoPago" => "El tipo de pago es obligatorio",
            "validation_idioma" => "El idioma es obligatorio",
            "validation_nombre" => "El nombre es obligatorio",
            "validation_descripcion" => "La descripción es obligatoria",
        ],
        "en" => [
            "validation_success" => "Validation successful",
            "validation_error" => "Validation errors",
            "validation_tipoProducto" => "Product type is required",
            "validation_tipoPago" => "Payment type is required",
            "validation_idioma" => "Language is required",
            "validation_nombre" => "Name is required",
            "validation_descripcion" => "Description is required",
        ]
    ];

    public static function validar(ProductoInputDto $dto, string $lang, TipoProductoService $tipoProductoService, TipoPagoService $tipoPagoService, LenguajeService $lenguajeService): RespuestaEntity
    {
        $errores = [];

        self::validarIdTipoProducto($dto->IdTipoProducto, $errores, $lang, $tipoProductoService);
        self::validarIdTipoPago($dto->IdTipoPago, $errores, $lang, $tipoPagoService);
        self::validarIdLenguaje($dto->IdLenguaje, $errores, $lang, $lenguajeService);
        self::validarNombre($dto->Nombre, $errores, $lang);
        self::validarDescripcion($dto->Descripcion, $errores, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }


    private static function validarIdTipoProducto(?int $valor, array &$errores, string $lang, TipoProductoService $tipoProductoService): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idTipoProducto"] = self::$translations[$lang]['validation_tipoProducto'];
            return;
        }
        $resp = $tipoProductoService->GetById($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["tipoProducto"] = $resp->Message;
        }

    }

    private static function validarIdTipoPago(?int $valor, array &$errores, string $lang, TipoPagoService $tipoPagoService): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idTipoPago"] =  self::$translations[$lang]['validation_tipoPago'];
            return;

        }
        $resp = $tipoPagoService->GetById($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["tipoPago"] = $resp->Message;
        }

    }


    private static function validarIdLenguaje(?int $valor, array &$errores, string $lang, LenguajeService $lenguajeService): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idLenguaje"] = self::$translations[$lang]['validation_idioma'];
            return;

        }
        $resp = $lenguajeService->GetById($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["idioma"] = $resp->Message;
        }

    }

    private static function validarNombre(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["nombre"] = self::$translations[$lang]['validation_nombre'];
          
        }

    }

    private static function validarDescripcion(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores["descripcion"] = self::$translations[$lang]['validation_descripcion'];
           
        }

    }
}
