<?php

namespace App\Application\Validations;

use App\Application\DTOs\Producto\DetallePlanInputDto;
use App\Application\Services\ProductoPlanService;
use App\Application\Services\ProductoService;
use App\Application\Services\SkuService;
use App\Domain\Entity\RespuestaEntity;

class DetallePlanValidation
{
    private static array $translations = [
        'es' => [
            'validation_success' => 'Validación correcta',
            'validation_error' => 'Errores de validación',
            'owner_invalid' => 'El owner no es dueño del producto',
            'type_product_invalid' => 'El tipo de producto no es valido',
            'validation_idplan' => 'El id del plan es requerido',
            'validation_detalle' => 'El detalle es requerido',
        ],
        'en' => [
            'validation_success' => 'Validation successful',
            'validation_error' => 'Validation errors',
            'owner_invalid' => 'The owner does not own the product',
            'type_product_invalid' => 'The product type is invalid',
            'validation_idplan' => 'Plan id is required',
            'validation_detalle' => 'Detail is required',
        ],
    ];

    public static function validar(DetallePlanInputDto $dto, string $lang, int $ownerId, ProductoPlanService $productoPlanService, SkuService $skuService, ProductoService $productoService): RespuestaEntity
    {
        $errores = [];

        self::validarPlan($dto->IdProductoPlan, $errores, $lang, $ownerId, $productoPlanService, $skuService, $productoService);
        self::validarDetalle($dto->Detalle, $errores, $lang);

        return new RespuestaEntity(
            empty($errores) ? self::$translations[$lang]['validation_success'] : self::$translations[$lang]['validation_error'],
            empty($errores),
            $errores
        );
    }

    private static function validarPlan(?int $valor, array &$errores, string $lang, int $ownerId, ProductoPlanService $productoPlanService, SkuService $skuService, ProductoService $productoService): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores['producto_plan'] = self::$translations[$lang]['validation_idplan'];

            return;
        }

        $resp = $productoPlanService->GetById($valor, $lang);
        if (! $resp->IsSuccess) {
            $errores['producto_plan'] = $resp->Message;

            return;
        }

        $respSku = $skuService->GetBySku($resp->Data->SkuProducto, $lang);
        if (! $resp->IsSuccess) {
            $errores['producto_plan'] = $respSku->Message;

            return;
        }

        $productoResp = $productoService->GetById($respSku->Data->IdProducto, $lang);

        if ($productoResp->Data->IdOwner != $ownerId) {
            $errores['producto_plan'] = self::$translations[$lang]['owner_invalid'];

            return;
        }

    }

    private static function validarDetalle(?string $valor, array &$errores, string $lang): void
    {
        if (empty($valor) || $valor <= 0) {
            $errores['detalle'] = self::$translations[$lang]['validation_detalle'];

            return;

        }

    }
}
