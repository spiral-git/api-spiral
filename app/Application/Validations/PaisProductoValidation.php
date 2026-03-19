<?php

namespace App\Application\Validations;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Services\PaisService;
use App\Application\Services\ProductoService;
use App\Application\Services\SkuService;
use App\Domain\Entity\RespuestaEntity;
use App\Infrastructure\Adapters\PaisProductoRepository;

class PaisProductoValidation
{
    private static array $translations = [
        'es' => [
            'validation_success' => 'Validación correcta',
            'validation_error' => 'Errores de validación',
            'country_required' => 'El id del pais es requerido',
            'sku_required' => 'El sku es obligatorio',
            'owner_invalid' => 'El owner no es dueño del producto',
            'pais_duplicado' => 'El país ya está asociado al producto',
        ],
        'en' => [
            'validation_success' => 'Validation successful',
            'validation_error' => 'Validation errors',
            'country_required' => 'Country id is required',
            'sku_required' => 'Sku is required',
            'owner_invalid' => 'The owner does not own the product',
            'pais_duplicado' => 'The country is already associated with the product',
        ],
    ];

    public static function validar(PaisProductoInputDto $dto, string $lang, SkuService $skuService, PaisService $paisService, ProductoService $productoService, int $ownerId, PaisProductoRepository $repository): RespuestaEntity
    {
        $errores = [];

        self::validarSku($dto->SkuProducto, $errores, $skuService, $lang, $productoService, $ownerId);
        self::validarPais($dto->IdPais, $errores, $paisService, $lang, $repository, $dto->SkuProducto);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarPais(?int $valor, array &$errores, PaisService $paisService, string $lang, PaisProductoRepository $repository, string $sku): void
    {

        if (empty($valor) || $valor <= 0) {
            $errores['pais'] = self::$translations[$lang]['country_required'];

            return;
        }

        $resp = $paisService->GetById($valor, $lang);

        if (! $resp->IsSuccess) {
            $errores['pais'] = $resp->Message;

            return;
        }

        $existPais = $repository->ExistPaisProducto($valor, $sku, $lang);

        if ($existPais->IsSuccess) {
            $errores['pais'] = self::$translations[$lang]['pais_duplicado'];

            return;
        }

    }

    private static function validarSku(?string $valor, array &$errores, SkuService $skuService, string $lang, ProductoService $productoService, int $ownerId): void
    {
        if (empty($valor)) {
            $errores['sku'] = self::$translations[$lang]['sku_required'];

            return;

        }

        $resp = $skuService->GetBySku($valor, $lang);
        if (! $resp->IsSuccess) {
            $errores['sku_exist'] = $resp->Message;

            return;
        }

        $productoId = $resp->Data->IdProducto;
        $respProducto = $productoService->GetById($productoId, $lang);
        if (! $respProducto->IsSuccess) {
            $errores['producto'] = $respProducto->Message;

            return;
        }

        if ($respProducto->Data->IdOwner != $ownerId) {
            $errores['producto'] = self::$translations[$lang]['owner_invalid'];

            return;
        }

    }
}
