<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\GetProductoBasicoOutputDto;
use App\Application\DTOs\Producto\ProductoBasicoInputDto;
use App\Application\DTOs\Producto\ProductoBasicoOutputDto;
use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Validations\ProductoBasicoValidation;
use App\Domain\Entity\ProductoBasicoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoBasicoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoBasicoService
{
    protected SkuService $_skuService;

    protected ProductoSetupService $_productoSetupService;

    protected ProductoService $_productoService;

    protected TipoSetupService $_tipoSetupService;

    protected TipoDescuentoService $_tipoDescuentoService;

    protected IProductoBasicoRepository $_repository;

    protected TipoProductoService $_tipoProductoService;

    protected ImagenProductoService $_imagenProductoService;

    protected PaisProductoService $_paisProductoService;

    protected ProductoCategoriaService $_categoriaProductoService;

    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Producto básico creado exitosamente',
            'error_created' => 'Error al crear el producto básico',
            'product_get_success' => 'Producto obtenido correctamente',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Basic product created successfully',
            'error_created' => 'Error creating basic product',
            'product_get_success' => 'Product retrieved successfully',
        ],
    ];

    public function __construct(IProductoBasicoRepository $repository, TipoProductoService $tipoProductoService, SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoDescuentoService $tipoDescuentoService, ImagenProductoService $imagenProductoService, PaisProductoService $paisProductoService, ProductoCategoriaService $categoriaProductoService)
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

    }

    public function Created(ProductoBasicoInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoBasicoValidation::validar($dto, $this->_productoService, $this->_tipoSetupService, $lang, $this->_tipoDescuentoService, $ownerId, $this->_tipoProductoService, $this->_skuService);

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

            $productoBasicoEntity = new ProductoBasicoEntity;
            $productoBasicoEntity->SkuProducto = $skuResp->Data->Sku;
            $productoBasicoEntity->Precio = $dto->Precio;
            $productoBasicoEntity->Descuento = $dto->Descuento;
            $productoBasicoEntity->IdTipoDescuento = $dto->IdTipoDescuento;

            $productoBasicoResp = $this->_repository->Create($productoBasicoEntity, $lang);
            if (! $productoBasicoResp->IsSuccess) {
                DB::rollBack();

                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            $output = new ProductoBasicoOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;
            $output->ProductoBasico = $productoBasicoResp->Data;

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

    //         $productoBasicoResp = $this->_repository->GetBySku($sku, $lang);
    //         if (! $productoBasicoResp->IsSuccess) {
    //             return $productoBasicoResp;
    //         }

    //         $output = new GetProductoBasicoOutputDto;
    //         $output->Sku = $skuResp->Data;
    //         $output->Setup = $setupResp->Data;
    //         $output->Producto = $dataProductoResp->Data;
    //         $output->ProductoBasico = $productoBasicoResp->Data;

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

    public function GetById(string $lang, string $id): RespuestaEntity
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

            $productoBasicoResp = $this->_repository->GetBySku($skuResp->Data->Sku, $lang);
            if (! $productoBasicoResp->IsSuccess) {
                return $productoBasicoResp;
            }

            $output = new GetProductoBasicoOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;
            $output->Producto = $dataProductoResp->Data;
            $output->ProductoBasico = $productoBasicoResp->Data;
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
