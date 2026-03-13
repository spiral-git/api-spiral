<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Application\Mappers\MapperProductos;
use App\Application\Validations\ProductoValidation;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductoService
{

    protected IProductoRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];
    public function __construct(IProductoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function crearProducto(ProductoInputDto $dto, string $lang): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoValidation::validar($dto);

            if (!$validacionesResp->IsSuccess) {
                return $validacionesResp;
            }

            $productoEntity = MapperProductos::inputDtoToEntity($dto);
            return $this->_repository->Create($productoEntity, $lang);

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetById(int $id, string $lang): RespuestaEntity
    {
        try {

            return $this->_repository->GetById($id, $lang);

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

}



