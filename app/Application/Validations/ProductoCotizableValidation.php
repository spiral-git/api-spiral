<?php

namespace App\Application\Validations;

use App\Application\DTOs\Producto\ProductoCotizableDto;
use App\Application\Services\ProductoService;
use App\Application\Services\SkuService;
use App\Application\Services\TipoProductoService;
use App\Application\Services\TipoSetupService;
use App\Domain\Entity\RespuestaEntity;

class ProductoCotizableValidation
{
    private static array $translations = [
        'es' => [
            'validation_success' => 'Validación correcta',
            'validation_error' => 'Errores de validación',
            'validation_idproducto' => 'El id del producto es obligatorio',
            'validation_id_tiposetup' => 'El Id del setup es obligatorio',
            'owner_invalid' => 'El owner no es dueño del producto',
            'type_product_invalid' => 'El tipo de producto no es valido',
            'exist_product' => 'Ya existe el producto cotizable',
        ],
        'en' => [
            'validation_success' => 'Validation successful',
            'validation_error' => 'Validation errors',
            'validation_idproducto' => 'Product id is required',
            'validation_id_tiposetup' => 'Setup id is required',
            'owner_invalid' => 'The owner does not own the product',
            'type_product_invalid' => 'The product type is invalid',
            'exist_product' => 'The quotable product already exists',
        ],
    ];

    public static function validar(ProductoCotizableDto $dto, ProductoService $productoService, TipoSetupService $tipoSetupService, string $lang, int $ownerId, TipoProductoService $tipoProductoService, SkuService $skuService): RespuestaEntity
    {
        $errores = [];

        self::validarProducto($dto->IdProducto, $errores, $productoService, $lang, $ownerId, $tipoProductoService, $skuService);
        self::validarTipoSetup($dto->IdTipoSetup, $errores, $tipoSetupService, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarProducto(?int $valor, array &$errores, ProductoService $productoService, string $lang, int $ownerId, TipoProductoService $tipoProductoService, SkuService $skuService): void
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

        $respTipoProducto = $tipoProductoService->GetByName('COTIZACION', $lang);

        if (! $respTipoProducto->IsSuccess) {
            $errores['producto'] = $respTipoProducto->Message;

            return;
        }

        if ($respTipoProducto->Data->Id != $resp->Data->IdTipoProducto) {
            $errores['producto'] = self::$translations[$lang]['type_product_invalid'];

            return;
        }

        $existProducto = $skuService->GetByProducto($resp->Data->Id, $lang);
        if ($existProducto->IsSuccess) {
            $errores['producto'] = self::$translations[$lang]['exist_product'];

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
}
