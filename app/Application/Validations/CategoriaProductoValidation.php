<?php

namespace App\Application\Validations;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Services\CategoriaService;
use App\Application\Services\ProductoService;
use App\Domain\Entity\RespuestaEntity;

class CategoriaProductoValidation
{

    private static array $translations = [
        "es" => [
            "validation_success" => "Validación correcta",
            "validation_error" => "Errores de validación",
            "product_id_required" => "El id del producto es obligatorio",
            "category_required" => "Debe enviar al menos una categoría del producto",
            "owner_invalid" => "El owner no es dueño del producto",
        ],
        "en" => [
            "validation_success" => "Validation successful",
            "validation_error" => "Validation errors",
            "product_id_required" => "Product id is required",
            "category_required" => "You must send at least one product category",
            "owner_invalid" => "The owner does not own the product",
        ]
    ];

    public static function validar(CategoriaProductoDto $dto, ProductoService $productoService, CategoriaService $categoriaService, string $lang, int $ownerId): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores, $productoService, $lang, $ownerId);
        self::validarCategorias($dto->Categorias, $errores, $categoriaService, $lang);

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

        if ($resp->Data->IdOwner != $ownerId) {
            $errores["producto"] = self::$translations[$lang]['owner_invalid'];
            return;
        }
    }

    private static function validarCategorias(?array $valor, array &$errores, CategoriaService $categoriaService, string $lang): void
    {
        if (empty($valor)) {
            $errores["categorias"] = self::$translations[$lang]['category_required'];
            return;

        }

        foreach ($valor as $idCat) {

            $resp = $categoriaService->GetById($idCat, $lang);

            if (!$resp->IsSuccess) {
                $errores["categorias_$idCat"] = $resp->Message;
            }
        }

    }


}
