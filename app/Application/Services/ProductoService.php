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
            DB::rollBack();
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

}


//  if ($dto->IdTipoProducto == $respCotizacion->Data->Id) {
//                 $idProducto = $productoResp->Data->Id;

//                 $skuResp = $this->_serviceSku->crearSku($idProducto, $dto->MaximoRecursos, $dto->IdTipoSetup, $lang);
//                 if (!$skuResp->IsSuccess) {
//                     return $skuResp;
//                 }

//                 $setupResp = $this->_serviceSetup->Create($dto->IdTipoSetup, $dto->AmountSetup, $lang);
//                 if (!$setupResp->IsSuccess) {
//                     return $setupResp;
//                 }

//                 $output = new ProductoCotizableOutputDto();
//                 $output->sku = $skuResp->Data->Sku;
//                 $output->producto = $productoResp->Data;

//                 DB::commit();
//                 return new RespuestaEntity(
//                     $this->translations[$lang]['product_created'] ?? "",
//                     false,
//                     $output
//                 );

//             }
