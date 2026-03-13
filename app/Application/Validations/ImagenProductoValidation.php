<?php

namespace App\Application\Validations;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Services\ProductoService;
use App\Domain\Entity\RespuestaEntity;

class ImagenProductoValidation
{

    private static array $translations = [
        "es" => [
            "validation_success" => "Validación correcta",
            "validation_error" => "Errores de validación",
            "product_id_required" => "El id del producto es obligatorio",
            "image_required" => "Debe enviar al menos una imagen"
        ],
        "en" => [
            "validation_success" => "Validation successful",
            "validation_error" => "Validation errors",
            "product_id_required" => "Product id is required",
            "image_required" => "You must send at least one image"
        ]
    ];

    public static function validar(ImagenProductoInputDto $dto, ProductoService $productoService, string $lang): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores, $productoService, $lang);
        self::validarImagenes($dto->Imagenes, $errores, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarIdProducto(?int $valor, array &$errores, ProductoService $productoService, string $lang): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idProducto"] = self::$translations[$lang]['product_id_required'];
            return;

        }

        $resp = $productoService->GetById($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["producto"] = $resp->Message;
        }

    }

    private static function validarImagenes(?array $valor, array &$errores, $lang): void
    {
        if (empty($valor)) {
            $errores["imagenes"] = self::$translations[$lang]['image_required'];;
        }
    }
}
