<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\GetProductoPlanOutputDto;
use App\Application\DTOs\Producto\GetProductoPlanOutputDtoBase;
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

    protected ImagenProductoService $_imagenProductoService;

    protected PaisProductoService $_paisProductoService;

    protected ProductoCategoriaService $_categoriaProductoService;
    protected PlanDetalleService $_planDetalleService;


    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Plan producto creado exitosamente',
            'error_created' => 'Error al crear el plan de producto',
            'product_get_success' => 'Producto obtenido correctamente',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Product plan created successfully',
            'error_created' => 'Error creating product plan',
            'product_get_success' => 'Product retrieved successfully',
        ],
    ];

    public function __construct(IProductoPlanRepository $repository, TipoProductoService $tipoProductoService, SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoDescuentoService $tipoDescuentoService, ImagenProductoService $imagenProductoService, PaisProductoService $paisProductoService, ProductoCategoriaService $categoriaProductoService, PlanDetalleService $planDetalleService)
    {
        $this->_skuService = $skuService;
        $this->_productoSetupService = $productoSetupService;
        $this->_productoService = $productoService;
        $this->_tipoSetupService = $tipoSetupService;
        $this->_tipoDescuentoService = $tipoDescuentoService;
        $this->_repository = $repository;
        $this->_tipoProductoService = $tipoProductoService;
        $this->_categoriaProductoService = $categoriaProductoService;
        $this->_paisProductoService = $paisProductoService;
        $this->_imagenProductoService = $imagenProductoService;
        $this->_planDetalleService = $planDetalleService;

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

    public function GetById(string $lang, string $id): RespuestaEntity
    {
        try {

            $skuResp = $this->_skuService->GetAllByProducto($id, $lang);

            if (! $skuResp->IsSuccess) {
                return $skuResp;
            }

            $imagenesProductoResp = $this->_imagenProductoService->GetAllByProducto($id, $lang);
            if (! $imagenesProductoResp->IsSuccess) {
                return $imagenesProductoResp;
            }

            $categoriaProductoResp = $this->_categoriaProductoService->GetAllByProducto($id, $lang);
            if (! $categoriaProductoResp->IsSuccess) {
                return $categoriaProductoResp;
            }

            $dataProductoResp = $this->_productoService->GetById($id, $lang);
            if (! $dataProductoResp->IsSuccess) {
                return $dataProductoResp;
            }

            $outputs = [];

            foreach ($skuResp->Data as $Sku) {

                $setupResp = $this->_productoSetupService->GetById($Sku->IdSetupProducto, $lang);
                if (! $setupResp->IsSuccess) {
                    return $setupResp;
                }

                $paisesProductoResp = $this->_paisProductoService->GetAllBySku($Sku->Sku, $lang);
                if (! $paisesProductoResp->IsSuccess) {
                    return $paisesProductoResp;
                }

                $productoPlanResp = $this->_repository->GetBySku($Sku->Sku, $lang);
                if (! $productoPlanResp->IsSuccess) {
                    return $productoPlanResp;
                }

                $detallesPlanResp = $this->_planDetalleService->GetById($productoPlanResp->Data->Id, $lang);
                if (! $detallesPlanResp->IsSuccess) {
                    return $detallesPlanResp;
                }


                $output = new GetProductoPlanOutputDtoBase;
                $output->Sku = $skuResp->Data;
                $output->Setup = $setupResp->Data;
                $output->ProductoPlan = $productoPlanResp->Data;
                $output->DetallesPlan = $detallesPlanResp->Data;
                $output->Paises = $paisesProductoResp->Data;

                $outputs[] = $output;
            }

            $outputPlan = new GetProductoPlanOutputDto;
            $outputPlan->Imagenes = $imagenesProductoResp->Data;
            $outputPlan->Variantes = $outputs;
            $outputPlan->Producto = $dataProductoResp->Data;
            $outputPlan->Categorias = $categoriaProductoResp->Data;

            return new RespuestaEntity(
                $this->translations[$lang]['product_get_success'] ?? '',
                true,
                $outputPlan
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
