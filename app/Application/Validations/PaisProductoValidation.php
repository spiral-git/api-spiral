<?php

namespace App\Application\Validations;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Services\PaisService;
use App\Application\Services\ProductoService;
use App\Application\Services\SkuService;
use App\Domain\Entity\RespuestaEntity;
use App\Infrastructure\Adapters\SkuRepository;

class PaisProductoValidation
{

    private static array $translations = [
        "es" => [
            "validation_success" => "Validación correcta",
            "validation_error" => "Errores de validación",
            "country_required" => "Debe enviar al menos un país",
            "sku_required" => "El sku es obligatorio",
            "owner_invalid" => "El owner no es dueño del producto",
        ],
        "en" => [
            "validation_success" => "Validation successful",
            "validation_error" => "Validation errors",
            "country_required" => "You must send at least one country",
            "sku_required" => "Sku is required",
             "owner_invalid" => "The owner does not own the product",
        ]
    ];
    public static function validar(PaisProductoInputDto $dto, string $lang, SkuService $skuService, PaisService $paisService, ProductoService $productoService, int $ownerId): RespuestaEntity
    {
        $errores = [];

        self::validarPaises($dto->Paises, $errores, $paisService, $lang);
        self::validarSku($dto->SkuProducto, $errores, $skuService, $lang, $productoService, $ownerId);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }


    private static function validarPaises(?array $valor, array &$errores, PaisService $paisService, string $lang): void
    {
        if (empty($valor)) {
            $errores["paises"] = self::$translations[$lang]['country_required'];
            return;

        }

        foreach ($valor as $idPais) {

            $resp = $paisService->GetById($idPais, $lang);

            if (!$resp->IsSuccess) {
                $errores["pais_$idPais"] = $resp->Message;
            }
        }
    }

    private static function validarSku(?string $valor, array &$errores, SkuService $skuService, string $lang, ProductoService $productoService, int $ownerId): void
    {
        if (empty($valor)) {
            $errores["sku"] = self::$translations[$lang]['sku_required'];
            return;

        }

        $resp = $skuService->GetBySku($valor, $lang);
        if (!$resp->IsSuccess) {
            $errores["sku_exist"] = $resp->Message;
            return;
        }

        $productoId = $resp->Data->IdProducto;
        $respProducto = $productoService->GetById($productoId, $lang);
        if (!$respProducto->IsSuccess) {
            $errores["producto"] = $respProducto->Message;
            return;
        }

        if($respProducto->Data->IdOwner != $ownerId){
            $errores["producto"] = self::$translations[$lang]['owner_invalid'];
            return;
        }

    }
}
