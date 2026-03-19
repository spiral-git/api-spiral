<?php

namespace App\Application\Validations;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Services\CategoriaService;
use App\Application\Services\ProductoService;
use App\Domain\Entity\RespuestaEntity;
use App\Infrastructure\Adapters\CategoriaProductoRepository;

class CategoriaProductoValidation
{

    private static array $translations = [
    "es" => [
        "validation_success" => "Validación correcta",
        "validation_error" => "Errores de validación",
        "product_id_required" => "El id del producto es obligatorio",
        "category_required" => "El id de la categoria es requerido",
        "owner_invalid" => "El owner no es dueño del producto",
        "categoria_duplicado" => "Ya la categoria esta asociada al producto"
    ],
    "en" => [
        "validation_success" => "Validation successful",
        "validation_error" => "Validation errors",
        "product_id_required" => "Product id is required",
        "category_required" => "Category id is required",
        "owner_invalid" => "The owner does not own the product",
        "categoria_duplicado" => "The category is already associated with the product"
    ]
];

    public static function validar(CategoriaProductoDto $dto, ProductoService $productoService, CategoriaService $categoriaService, string $lang, int $ownerId, CategoriaProductoRepository $repository): RespuestaEntity
    {
        $errores = [];

        self::validarIdProducto($dto->IdProducto, $errores, $productoService, $lang, $ownerId);
        self::validarCategoria($dto->IdCategoria, $errores, $categoriaService, $lang, $repository, $dto->IdProducto);

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

    private static function validarCategoria(?int $valor, array &$errores, CategoriaService $categoriaService, string $lang, CategoriaProductoRepository $repository, int $idProducto): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores["categoria"] = self::$translations[$lang]['category_required'];
            return;

        }

         $resp = $categoriaService->GetById($valor, $lang); 

            if (!$resp->IsSuccess) {
                $errores["categoria"] = $resp->Message;
            }

        $existCategoriaProducto = $repository->ExistCategoriaProducto($valor, $idProducto, $lang);

        if ($existCategoriaProducto->IsSuccess) {
            $errores['categoria'] = self::$translations[$lang]['categoria_duplicado'];

            return;
        }

    }


}
