<?php

namespace App\Application\Services;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Validations\CategoriaProductoValidation;
use App\Domain\Entity\CategoriaProductoEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ICategoriaProductoRepository;
use App\Infrastructure\Adapters\CategoriaProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoCategoriaService
{
    protected ICategoriaProductoRepository $_repository;
    protected ProductoService $_productoService;
    protected CategoriaService $_categoriaService;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "success_created" => "Categorías asociadas al producto correctamente",
            "error_created" => "Presentamos un error al asociar las categorías al producto"
        ],
        "en" => [
            "error" => "An error occurred",
            "success_created" => "Categories successfully associated with the product",
            "error_created" => "An error occurred while associating categories with the product"
        ]
    ];

    public function __construct(CategoriaProductoRepository $repository, CategoriaService $categoriaService, ProductoService $productoService)
    {
        $this->_repository = $repository;
        $this->_categoriaService = $categoriaService;
        $this->_productoService = $productoService;
    }

    public function Create(CategoriaProductoDto $dto, string $lang, int $ownerId): RespuestaEntity
    {
        DB::beginTransaction();
        try {

            $respValidation = CategoriaProductoValidation::validar($dto, $this->_productoService, $this->_categoriaService, $lang, $ownerId);
            if (!$respValidation->IsSuccess) {
                return $respValidation;
            }
            
            foreach ($dto->Categorias as $categoria) {
                $entity = new CategoriaProductoEntity();
                $entity->IdProducto = $dto->IdProducto;
                $entity->IdCategoria = $categoria;

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
