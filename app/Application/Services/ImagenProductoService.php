<?php

namespace App\Application\Services;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Validations\ImagenProductoValidation;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IImagenRepository;
use Exception;

class ImagenProductoService
{
    protected IImagenRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];
    public function __construct(IImagenRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(ImagenProductoInputDto $dto, string $lang): RespuestaEntity
    {
        try {

            $respValidation = ImagenProductoValidation::validar($dto);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }

            $entity = new ImagenProductoEntity();
            $entity->IdProducto = $dto->IdProducto;
            $entity->Ruta = $dto->Ruta;
            $entity->Status = true;

            return $this->_repository->Create($entity, $lang);
            
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Update(ImagenProductoEntity $entity, string $lang): RespuestaEntity
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

    public function GetByRuta(string $ruta, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetByRuta($ruta, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetAllByProducto(int $id, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetAllByProducto($id, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Delete(int $id, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->Delete($id, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
