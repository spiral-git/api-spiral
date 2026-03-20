<?php

namespace App\Application\Services;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Application\Mappers\MapperProductos;
use App\Application\Services\LenguajeService;
use App\Application\Services\TipoPagoService;
use App\Application\Services\TipoProductoService;
use App\Application\Validations\ProductoValidation;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IProductoRepository;
use App\Infrastructure\Adapters\ProductoRepository;
use Exception;

class ProductoService
{

    protected IProductoRepository $_repository;
    protected TipoProductoService $_tipoProductoService;
    protected TipoPagoService $_tipoPagoService;
    protected LenguajeService $_lenguajeService;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ], 
        "en" => [
            "error" => "An error occurred"
        ]
    ];
    public function __construct(IProductoRepository $repository,TipoProductoService $tipoProductoService, TipoPagoService $tipoPagoService, LenguajeService $lenguajeService)
    {
        $this->_repository = $repository;
        $this->_tipoProductoService = $tipoProductoService;
        $this->_tipoPagoService = $tipoPagoService;
        $this->_lenguajeService = $lenguajeService;
    }

    public function crearProducto(ProductoInputDto $dto, string $lang): RespuestaEntity
    {
        try {

            $validacionesResp = ProductoValidation::validar($dto, $lang, $this->_tipoProductoService, $this->_tipoPagoService, $this->_lenguajeService);

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



