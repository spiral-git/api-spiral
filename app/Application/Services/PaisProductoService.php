<?php

namespace App\Application\Services;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Validations\PaisProductoValidation;
use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IPaisProductoRepository;
use App\Infrastructure\Adapters\PaisProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class PaisProductoService
{
    protected IPaisProductoRepository $_repository;
    protected SkuService $_skuService; 
    protected PaisService $_paisService;
    protected ProductoService $_productoService;


    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "success_created" => "Paises asociados al producto correctamente",
            "error_created" => "Presentamos un error al asociar los paises al producto"
        ],
        "en" => [
            "error" => "An error occurred",
            "success_created" => "Countries successfully associated with the product",
            "error_created" => "An error occurred while associating countries with the product"
        ]
    ];

    public function __construct(IPaisProductoRepository $repository, SkuService $skuService, PaisService $paisService, ProductoService $productoService)
    {
        $this->_repository = $repository;
        $this->_productoService = $productoService;
        $this->_paisService = $paisService;
        $this->_skuService = $skuService;
    }

    public function Create(PaisProductoInputDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        try {

            $respValidation = PaisProductoValidation::validar($dto, $lang, $this->_skuService, $this->_paisService, $this->_productoService, $ownerId, $this->_repository);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }

                $entity = new PaisProductoEntity();
                $entity->IdPais = $dto->IdPais;
                $entity->SkuProducto = $dto->SkuProducto;

                $resp = $this->_repository->Create($entity, $lang);
                if (!$resp->IsSuccess) {
                    return new RespuestaEntity(
                        $this->translations[$lang]['error_created'] ?? "",
                        false,
                        null
                    );
                }

            return new RespuestaEntity(
                $this->translations[$lang]['success_created'] ?? "",
                true,
                null
            );


        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
    public function GetAllBySku(string $sku, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetAllBySku($sku, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
    public function Update(PaisProductoEntity $entity, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->Update($entity, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
    public function Delete(string $sku, int $idPais, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->Delete($sku, $idPais, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
