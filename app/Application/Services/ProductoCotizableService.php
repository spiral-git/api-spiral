<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\ProductoCotizableDto;
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
    protected TipoSetupService $_tipoSetupService;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "success_created" => "Producto de cotización creado exitosamente",
            "error_created" => "Error al crear el producto de cotización"
        ],
        "en" => [
            "error" => "An error occurred",
            "success_created" => "Quotation product created successfully",
            "error_created" => "Error creating the quotation product"
        ]
    ];
    
    public function __construct(SkuService $skuService, ProductoSetupService $productoSetupService, ProductoService $productoService, TipoSetupService $tipoSetupService)
    {
        $this->_skuService = $skuService;
        $this->_productoSetupService = $productoSetupService;
        $this->_productoService = $productoService;
        $this->_tipoSetupService = $tipoSetupService;
    }

    public function Created(ProductoCotizableDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoCotizableValidation::validar($dto, $this->_productoService,  $this->_tipoSetupService, $lang, $ownerId);
            $dto->AmountSetup = 0;
            $dto->MaximoRecursos = 0;

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

