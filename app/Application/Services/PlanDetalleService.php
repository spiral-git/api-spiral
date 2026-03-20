<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\DetallePlanInputDto;
use App\Application\Validations\DetallePlanValidation;
use App\Domain\Entity\DetallePlanEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IDetallePlanRepository;
use Exception;

class PlanDetalleService
{
    protected SkuService $_skuService;

    protected ProductoService $_productoService;

    protected ProductoPlanService $_productoPlanService;

    protected IDetallePlanRepository $_repository;

    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Detalle del plan creado exitosamente',
            'error_created' => 'Error al crear el detalle del plan',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Plan detail created successfully',
            'error_created' => 'Error creating plan detail',
        ],
    ];

    public function __construct(IDetallePlanRepository $repository, SkuService $skuService, ProductoService $productoService, ProductoPlanService $productoPlanService)
    {
        $this->_skuService = $skuService;
        $this->_productoService = $productoService;
        $this->_repository = $repository;
        $this->_productoPlanService = $productoPlanService;

    }

    public function Created(DetallePlanInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = DetallePlanValidation::validar($dto, $lang, $ownerId, $this->_productoPlanService, $this->_skuService, $this->_productoService);

            if (! $validacionesResp->IsSuccess) {
                return $validacionesResp;
            }

            $detallePlan = new DetallePlanEntity;
            $detallePlan->Detalle = $dto->Detalle;
            $detallePlan->IdProductoPlan = $dto->IdProductoPlan;

            $detalleResp = $this->_repository->Create($detallePlan, $lang);

            if (! $detalleResp->IsSuccess) {
                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['success_created'] ?? '',
                true,
                $detalleResp->Data
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }
}
