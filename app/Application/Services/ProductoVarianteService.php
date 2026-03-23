<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\GetProductoVarianteOutputBase;
use App\Application\DTOs\Producto\GetProductoVarianteOutputDto;
use App\Application\DTOs\Producto\ProductoVarianteInputDto;
use App\Application\DTOs\Producto\ProductoVarianteOutputDto;
use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Validations\ProductoVarianteValidation;
use App\Domain\Entity\ProductoVarianteEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoVarianteRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoVarianteService
{
    protected SkuService $_skuService;

    protected ProductoSetupService $_productoSetupService;

    protected ProductoService $_productoService;

    protected TipoSetupService $_tipoSetupService;

    protected TipoDescuentoService $_tipoDescuentoService;

    protected IProductoVarianteRepository $_repository;

    protected TipoProductoService $_tipoProductoService;

    protected ImagenProductoService $_imagenProductoService;

    protected PaisProductoService $_paisProductoService;

    protected ProductoCategoriaService $_categoriaProductoService;

    private array $translations = [
        'es' => [
            'error' => 'Ocurrió un error',
            'success_created' => 'Producto variante creado exitosamente',
            'error_created' => 'Error al crear el producto variante',
            'product_get_success' => 'Producto obtenido correctamente',
        ],
        'en' => [
            'error' => 'An error occurred',
            'success_created' => 'Variant product created successfully',
            'error_created' => 'Error creating variant product',
            'product_get_success' => 'Product retrieved successfully',
        ],
    ];

    public function __construct(IProductoVarianteRepository $repository, TipoProductoService $tipoProductoService, SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoDescuentoService $tipoDescuentoService, ImagenProductoService $imagenProductoService, PaisProductoService $paisProductoService, ProductoCategoriaService $categoriaProductoService)
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

    public function Created(ProductoVarianteInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoVarianteValidation::validar($dto, $this->_productoService, $this->_tipoSetupService, $lang, $this->_tipoDescuentoService, $ownerId, $this->_tipoProductoService);

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

            $productoEntity = new ProductoVarianteEntity;
            $productoEntity->SkuProducto = $skuResp->Data->Sku;
            $productoEntity->Precio = $dto->Precio;
            $productoEntity->Descuento = $dto->Descuento;
            $productoEntity->IdTipoDescuento = $dto->IdTipoDescuento;
            $productoEntity->Nombre = $dto->Nombre;

            $productoResp = $this->_repository->Create($productoEntity, $lang);
            if (! $productoResp->IsSuccess) {
                DB::rollBack();

                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? '',
                    false,
                    null
                );
            }

            $output = new ProductoVarianteOutputDto;
            $output->Sku = $skuResp->Data;
            $output->Setup = $setupResp->Data;
            $output->ProductoVariante = $productoResp->Data;

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

                $productoVarianteResp = $this->_repository->GetBySku($Sku->Sku, $lang);
                if (! $productoVarianteResp->IsSuccess) {
                    return $productoVarianteResp;
                }

                $output = new GetProductoVarianteOutputBase;
                $output->Sku = $skuResp->Data;
                $output->Setup = $setupResp->Data;
                $output->ProductoVariante = $productoVarianteResp->Data;
                $output->Paises = $paisesProductoResp->Data;

                $outputs[] = $output;
            }

            $outputVariantes = new GetProductoVarianteOutputDto;
            $outputVariantes->Imagenes = $imagenesProductoResp->Data;
            $outputVariantes->Variantes = $outputs;
            $outputVariantes->Producto = $dataProductoResp->Data;
            $outputVariantes->Categorias = $categoriaProductoResp->Data;

            return new RespuestaEntity(
                $this->translations[$lang]['product_get_success'] ?? '',
                true,
                $outputVariantes
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
