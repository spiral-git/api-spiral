<?php

namespace App\Application\Services;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Validations\PaisProductoValidation;
use App\Domain\Entity\PaisProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IPaisProductoRepository;
use App\Infrastructure\Adapters\PaisProductoRepository;
use Exception;

class PaisProductoService
{
    protected IPaisProductoRepository $_repository;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];


    public function __construct(PaisProductoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(PaisProductoInputDto $dto, string $lang): RespuestaEntity
    {
        try {

            $respValidation = PaisProductoValidation::validar($dto);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }

            $entity = new PaisProductoEntity();
            $entity->IdPais = $dto->IdPais;
            $entity->SkuProducto = $dto->SkuProducto;

            return $this->_repository->Create($entity, $lang);

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
