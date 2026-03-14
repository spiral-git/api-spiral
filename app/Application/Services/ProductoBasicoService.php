<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\ProductoBasicoInputDto;
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

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "success_created" => "Producto básico creado exitosamente",
            "error_created" => "Error al crear el producto básico"
        ],
        "en" => [
            "error" => "An error occurred",
            "success_created" => "Basic product created successfully",
            "error_created" => "Error creating basic product"
        ]
    ];

    public function __construct(IProductoBasicoRepository $repository, SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService, TipoDescuentoService $tipoDescuentoService)
    {
        $this->_skuService = $skuService;
        $this->_productoSetupService = $productoSetupService;
        $this->_productoService = $productoService;
        $this->_tipoSetupService = $tipoSetupService;
        $this->_tipoDescuentoService = $tipoDescuentoService;
        $this->_repository = $repository;
    }


    public function Created(ProductoBasicoInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoBasicoValidation::validar($dto, $this->_productoService, $this->_tipoSetupService, $lang, $this->_tipoDescuentoService, $ownerId);

            if (!$validacionesResp->IsSuccess) {
                return $validacionesResp;
            }

            $setupDto = new SetupInputDto();
            $setupDto->Amount = $dto->AmountSetup;
            $setupDto->IdTipoSetup = $dto->IdTipoSetup;

            $setupResp = $this->_productoSetupService->Create($setupDto, $lang);

            if (!$setupResp->IsSuccess) {
                DB::rollBack();
                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? "",
                    false,
                    null
                );
            }

            $skuDto = new SkuInputDto();
            $skuDto->IdProducto = $dto->IdProducto;
            $skuDto->IdSetup = $setupResp->Data->Id;
            $skuDto->MaximoRecursos = $dto->MaximoRecursos;

            $skuResp = $this->_skuService->crearSku($skuDto, $lang);
            if (!$skuResp->IsSuccess) {
                DB::rollBack();
                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? "",
                    false,
                    null
                );
            }

            $productoBasicoEntity = new ProductoBasicoEntity();
            $productoBasicoEntity->SkuProducto = $skuResp->Data->Sku;
            $productoBasicoEntity->Precio = $dto->Precio;
            $productoBasicoEntity->Descuento = $dto->Descuento;
            $productoBasicoEntity->IdTipoDescuento = $dto->IdTipoDescuento;

            $productoBasicoResp = $this->_repository->Create($productoBasicoEntity, $lang);
            if (!$skuResp->IsSuccess) {
                DB::rollBack();
                return new RespuestaEntity(
                    $this->translations[$lang]['error_created'] ?? "",
                    false,
                    null
                );
            }

            DB::commit();

            return new RespuestaEntity(
                $this->translations[$lang]['success_created'] ?? "",
                true,
                null
            );

        } catch (Exception $e) {
            DB::rollBack();
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }


}
