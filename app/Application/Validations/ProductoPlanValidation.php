<?php

namespace App\Application\Validations;

use App\Application\DTOs\Producto\ProductoPlanInputDto;
use App\Application\Services\ProductoService;
use App\Application\Services\TipoDescuentoService;
use App\Application\Services\TipoProductoService;
use App\Application\Services\TipoSetupService;
use App\Domain\Entity\RespuestaEntity;

class ProductoPlanValidation
{
    private static array $translations = [
    "es" => [
        "validation_success" => "Validación correcta",
        "validation_error" => "Errores de validación",
        "validation_idproducto" => "El id del producto es obligatorio",
        "validation_id_tiposetup" => "El Id del setup es obligatorio",
        "validation_id_tipodescuento" => "El Id del tipo de descuento es obligatorio",
        "owner_invalid" => "El owner no es dueño del producto",
        "type_product_invalid" => "El tipo de producto no es valido",
        "validation_name" => "El nombre es requerido",
        "validation_description" => "La descripción es requerida",
        "validation_tag" => "La etiqueta es requerida"
    ],
    "en" => [
        "validation_success" => "Validation successful",
        "validation_error" => "Validation errors",
        "validation_idproducto" => "Product id is required",
        "validation_id_tiposetup" => "Setup id is required",
        "validation_id_tipodescuento" => "Discount type id is required",
        "owner_invalid" => "The owner does not own the product",
        "type_product_invalid" => "Type product is invalid",
        "validation_name" => "Name is required",
        "validation_description" => "Description is required",
        "validation_tag" => "Tag is required"
    ]
];

    public static function validar(ProductoPlanInputDto $dto, ProductoService $productoService, TipoSetupService $tipoSetupService, string $lang, TipoDescuentoService $tipoDescuentoService, int $ownerId, TipoProductoService $tipoProductoService): RespuestaEntity
    {
        $errores = [];

        self::validarProducto($dto->IdProducto, $errores, $productoService, $lang, $ownerId, $tipoProductoService);
        self::validarTipoSetup($dto->IdTipoSetup, $errores, $tipoSetupService, $lang);
        self::validarNombre($dto->Nombre, $errores, $lang);
        self::validarDescripcion($dto->Descripcion, $errores, $lang);
        self::validarEtiqueta($dto->Etiqueta, $errores, $lang);
        self::validarTipoDescuento($dto->IdTipoDescuento, $errores, $tipoDescuentoService, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarProducto(?int $valor, array &$errores, ProductoService $productoService, string $lang, int $ownerId, TipoProductoService $tipoProductoService): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores['idProducto'] = self::$translations[$lang]['validation_idproducto'];

            return;

        }

        $resp = $productoService->GetById($valor, $lang);
        if (! $resp->IsSuccess) {
            $errores['producto'] = $resp->Message;

            return;
        }

        if ($resp->Data->IdOwner != $ownerId) {
            $errores['producto'] = self::$translations[$lang]['owner_invalid'];

            return;
        }

        $respTipoProducto = $tipoProductoService->GetByName('PLAN', $lang);

        if (! $respTipoProducto->IsSuccess) {
            $errores['producto'] = $respTipoProducto->Message;

            return;
        }

        if ($respTipoProducto->Data->Id != $resp->Data->IdTipoProducto) {
            $errores['producto'] = self::$translations[$lang]['type_product_invalid'];

            return;
        }
    }

    private static function validarTipoSetup(?int $valor, array &$errores, TipoSetupService $tipoSetupService, string $lang): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores['idTipoSetup'] = self::$translations[$lang]['validation_id_tiposetup'];

            return;

        }

        $resp = $tipoSetupService->GetById($valor, $lang);
        if (! $resp->IsSuccess) {
            $errores['tipoSetup'] = $resp->Message;
        }

    }

    private static function validarNombre(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores['nombre'] = self::$translations[$lang]['validation_name'];

            return;

        }

    }

    private static function validarDescripcion(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores['descripcion'] = self::$translations[$lang]['validation_description'];

            return;

        }

    }

    private static function validarEtiqueta(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor)) {
            $errores['etiqueta'] = self::$translations[$lang]['validation_tag'];

            return;

        }

    }

    private static function validarTipoDescuento(?int $valor, array &$errores, TipoDescuentoService $tipoDescuentoService, string $lang): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores['idTipoDescuento'] = self::$translations[$lang]['validation_id_tipodescuento'];

            return;

        }

        $resp = $tipoDescuentoService->GetById($valor, $lang);
        if (! $resp->IsSuccess) {
            $errores['tipoSetup'] = $resp->Message;
        }

    }
}
