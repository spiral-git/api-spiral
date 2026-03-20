<?php

namespace App\Application\Services;

use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\Validations\SetupValidation;
use App\Domain\Entity\ProductoSetupEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ISetupProductoRepository;
use App\Infrastructure\Adapters\ProductoSetupRepository;
use Exception;

class ProductoSetupService
{

    protected ISetupProductoRepository $_repository;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];


    public function __construct(ISetupProductoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(SetupInputDto $dto, string $lang): RespuestaEntity
    {
        try {

            $entity = new ProductoSetupEntity();
            $entity->IdTipoSetup = $dto->IdTipoSetup;
            $entity->Amount = $dto->Amount;
            return $this->_repository->Create($entity, $lang);
            
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
