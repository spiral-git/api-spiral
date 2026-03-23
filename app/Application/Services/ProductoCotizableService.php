<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\GetProductoCotizableOutputDto;
use App\Application\DTOs\Producto\ProductoCotizableDto;
use App\Application\DTOs\Producto\ProductoCotizableOutputDto;
use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Validations\ProductoCotizableValidation;
use App\Domain\Entity\RespuestaEntity;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoCotizableService
{
    protected SkuService $_skuService;

    protected ProductoSetupService $_productoSetupService;

    protected ProductoService $_productoService;

    protected TipoProductoService $_tipoProductoService;

    protected TipoSetupService $_tipoSetupService;

    protected ImagenProductoService $_imagenProductoService;

    protected PaisProductoService $_paisProductoService;

    protected ProductoCategoriaService $_categoriaProductoService;

    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Producto de cotización creado exitosamente',
            'error_created' => 'Error al crear el producto de cotización',
            'product_get_success' => 'Producto obtenido correctamente',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Quotation product created successfully',
            'error_created' => 'Error creating the quotation product',
            'product_get_success' => 'Product retrieved successfully',
        ],
    ];

    public function __construct(SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoProductoService $tipoProductoService, ImagenProductoService $imagenProductoService, PaisProductoService $paisProductoService, ProductoCategoriaService $categoriaProductoService)
    {
        $this->_skuService = $skuService;
        $this->_productoSetupService = $productoSetupService;
        $this->_productoService = $productoService;
        $this->_tipoSetupService = $tipoSetupService;
        $this->_tipoProductoService = $tipoProductoService;
        $this->_categoriaProductoService = $categoriaProductoService;
        $this->_paisProductoService = $paisProductoService;
        $this->_imagenProductoService = $imagenProductoService;
    }

    public function Created(ProductoCotizableDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoCotizableValidation::validar($dto, $this->_productoService, $this->_tipoSetupService, $lang, $ownerId, $this->_tipoProductoService, $this->_skuService);
            $dto->AmountSetup = 0;
            $dto->MaximoRecursos = 0;

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

            $output = new ProductoCotizableOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;

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

    // public function GetBySku(string $lang, string $sku): RespuestaEntity
    // {
    //     try {

    //         $skuResp = $this->_skuService->GetBySku($sku, $lang);

    //         if (! $skuResp->IsSuccess) {
    //             return $skuResp;
    //         }

    //         $setupResp = $this->_productoSetupService->GetById($skuResp->Data->IdSetupProducto, $lang);
    //         if (! $setupResp->IsSuccess) {
    //             return $setupResp;
    //         }

    //         $dataProductoResp = $this->_productoService->GetById($skuResp->Data->IdProducto, $lang);
    //         if (! $dataProductoResp->IsSuccess) {
    //             return $dataProductoResp;
    //         }

    //         $output = new GetProductoCotizableOutputDto;
    //         $output->Sku = $skuResp->Data;
    //         $output->Setup = $setupResp->Data;
    //         $output->Producto = $dataProductoResp->Data;

    //         return new RespuestaEntity(
    //             $this->translations[$lang]['product_get_success'] ?? '',
    //             true,
    //             $output
    //         );

    //     } catch (Exception $e) {
    //         return new RespuestaEntity(
    //             $this->translations[$lang]['error'] ?? '',
    //             false,
    //             null
    //         );

    //     }
    // }

    public function GetById(string $lang, int $id): RespuestaEntity
    {
        try {

            $skuResp = $this->_skuService->GetByProducto($id, $lang);

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

            $paisesProductoResp = $this->_paisProductoService->GetAllBySku($skuResp->Data->Sku, $lang);
            if (! $paisesProductoResp->IsSuccess) {
                return $paisesProductoResp;
            }

            $setupResp = $this->_productoSetupService->GetById($skuResp->Data->IdSetupProducto, $lang);
            if (! $setupResp->IsSuccess) {
                return $setupResp;
            }

            $dataProductoResp = $this->_productoService->GetById($skuResp->Data->IdProducto, $lang);
            if (! $dataProductoResp->IsSuccess) {
                return $dataProductoResp;
            }

            $output = new GetProductoCotizableOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;
            $output->Producto = $dataProductoResp->Data;
            $output->Paises = $paisesProductoResp->Data;
            $output->Categorias = $categoriaProductoResp->Data;
            $output->Imagenes = $imagenesProductoResp->Data;

            return new RespuestaEntity(
                $this->translations[$lang]['product_get_success'] ?? '',
                true,
                $output
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
