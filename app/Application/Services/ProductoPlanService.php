<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\ProductoPlanInputDto;
use App\Application\DTOs\Producto\ProductoPlanOutputDto;
use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Validations\ProductoPlanValidation;
use App\Domain\Entity\ProductoPlanEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoPlanRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoPlanService
{
    protected SkuService $_skuService;

    protected ProductoSetupService $_productoSetupService;

    protected ProductoService $_productoService;

    protected TipoSetupService $_tipoSetupService;

    protected TipoDescuentoService $_tipoDescuentoService;

    protected IProductoPlanRepository $_repository;

    protected TipoProductoService $_tipoProductoService;

    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Plan producto creado exitosamente',
            'error_created' => 'Error al crear el plan de producto',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Product plan created successfully',
            'error_created' => 'Error creating product plan',
        ],
    ];

    public function __construct(IProductoPlanRepository $repository, TipoProductoService $tipoProductoService, SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoDescuentoService $tipoDescuentoService)
    {
        $this->_skuService = $skuService;
        $this->_productoSetupService = $productoSetupService;
        $this->_productoService = $productoService;
        $this->_tipoSetupService = $tipoSetupService;
        $this->_tipoDescuentoService = $tipoDescuentoService;
        $this->_repository = $repository;
        $this->_tipoProductoService = $tipoProductoService;

    }

    public function Created(ProductoPlanInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoPlanValidation::validar($dto, $this->_productoService, $this->_tipoSetupService, $lang, $this->_tipoDescuentoService, $ownerId, $this->_tipoProductoService);

            if (! $validacionesResp->IsSuccess) {
                return $validacionesResp;
            }

            $setupDto = new SetupInputDto;
            $setupDto->Amount = $dto->AmountSetup;
            $setupDto->IdTipoSetup = $dto->IdTipoSetup;

            $setupResp = $this->_productoSetupService->Create($setupDto, $lang);

            if (! $setupResp->IsSuccess) {
                DB::rollBack();

                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            $skuDto = new SkuInputDto;
            $skuDto->IdProducto = $dto->IdProducto;
            $skuDto->IdSetup = $setupResp->Data->Id;
            $skuDto->MaximoRecursos = $dto->MaximoRecursos;

            $skuResp = $this->_skuService->crearSku($skuDto, $lang);
            if (! $skuResp->IsSuccess) {
                DB::rollBack();

                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            $productoEntity = new ProductoPlanEntity;
            $productoEntity->SkuProducto = $skuResp->Data->Sku;
            $productoEntity->Precio = $dto->Precio;
            $productoEntity->Descuento = $dto->Descuento;
            $productoEntity->IdTipoDescuento = $dto->IdTipoDescuento;
            $productoEntity->Nombre = $dto->Nombre;
            $productoEntity->Descripcion = $dto->Descripcion;
            $productoEntity->Etiqueta = $dto->Etiqueta;

            $productoResp = $this->_repository->Create($productoEntity, $lang);
            if (! $productoResp->IsSuccess) {
                DB::rollBack();

                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            $output = new ProductoPlanOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;
            $output->ProductoPlan = $productoResp->Data;

            DB::commit();

            return new RespuestaEntity(
                $this->translations[$lang]['success_created'] ?? '',
                true,
                $output
            );

        } catch (Exception $e) {
            DB::rollBack();

            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? '',
                false,
                null
            );
        }
    }
}
