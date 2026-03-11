<?php

namespace App\Application\Services;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Validations\CategoriaProductoValidation;
use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ICategoriaProductoRepository;
use App\Infrastructure\Adapters\CategoriaProductoRepository;
use Exception;

class ProductoCategoriaService
{
    protected ICategoriaProductoRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];

    public function __construct(CategoriaProductoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(CategoriaProductoDto $dto, string $lang): RespuestaEntity
    {
        try {

            $respValidation = CategoriaProductoValidation::validar($dto);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }

            $entity = new CategoriaProductoEntity();
            $entity->IdProducto = $dto->IdProducto;
            $entity->IdCategoria = $dto->IdCategoria;

            return $this->_repository->Create($entity, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Update(CategoriaProductoEntity $entity, string $lang): RespuestaEntity
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
}
