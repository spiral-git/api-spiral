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
        "owner_invalid" => "El owner no es dueño del producto",
        "image_required" => "Debe enviar al menos una imagen"
    ],
    "en" => [
        "validation_success" => "Validation successful",
        "validation_error" => "Validation errors",
        "product_id_required" => "Product id is required",
        "owner_invalid" => "The owner does not own the product",
        "image_required" => "You must send at least one image"
    ]
];

    public static function validar(ImagenProductoInputDto $dto, ProductoService $productoService, string $lang, int $ownerId): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores, $productoService, $lang, $ownerId);
        self::validarImagen($dto->Imagen, $errores, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarIdProducto(?int $valor, array &$errores, ProductoService $productoService, string $lang, int $ownerId): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["idProducto"] = self::$translations[$lang]['product_id_required'];
            return;

        }

        $resp = $productoService->GetById($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["producto"] = $resp->Message;
            return;
        }

        if($resp->Data->IdOwner != $ownerId){
            $errores["producto"] = self::$translations[$lang]['owner_invalid'];
            return;
        }

    }

    private static function validarImagen(?string $valor, array &$errores, $lang): void
    {
        if (empty($valor)) {
            $errores["imagen"] = self::$translations[$lang]['image_required'];;
        }
    }
}
