<?php

namespace App\Application\Services;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Validations\ImagenProductoValidation;
use App\Domain\Entity\ImagenProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IImagenRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ImagenProductoService
{
    protected IImagenRepository $_repository;
    protected ProductoService $_productoService;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "success_created" => "Imágenes asociadas al producto correctamente",
            "error_created" => "Presentamos un error al asociar las imágenes al producto"
        ],
        "en" => [
            "error" => "An error occurred",
            "success_created" => "Images successfully associated with the product",
            "error_created" => "An error occurred while associating images with the product"
        ]
    ];
    public function __construct(IImagenRepository $repository, ProductoService $productoService)
    {
        $this->_repository = $repository;
        $this->_productoService = $productoService;
    }

    public function Create(ImagenProductoInputDto $dto, string $lang): RespuestaEntity
    {
        DB::beginTransaction();
        try {

            $respValidation = ImagenProductoValidation::validar($dto, $this->_productoService, $lang);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }

            foreach ($dto->Imagenes as $imagen) {
                $entity = new ImagenProductoEntity();
                $entity->IdProducto = $dto->IdProducto;
                $entity->Ruta = $imagen;
                $entity->Status = true;

                $resp = $this->_repository->Create($entity, $lang);
                if (!$resp->IsSuccess) {
                    DB::rollBack();
                    return new RespuestaEntity(
                        $this->translations[$lang]['error_created'] ?? "",
                        false,
                        null
                    );
                }
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
