<?php

namespace App\Application\Services;

use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Validations\SkuValidation;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\SkuProductoEntity;
use App\Domain\Ports\ISkuRepository;
use App\Infrastructure\Adapters\SkuRepository;
use Illuminate\Support\Str;
use Exception;

class SkuService
{
    protected ISkuRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "error" => "An error occurred"
        ]
    ];

    public function __construct(SkuRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function CrearSku(SkuInputDto $dto, string $lang): RespuestaEntity
    {
        try {
            $isExito = false;

            $sku = new SkuProductoEntity();

            $sku->IdProducto = $dto->IdProducto;
            $sku->Status = "activo";
            $sku->MaximoRecursos = $dto->MaximoRecursos;
            $sku->IdSetupProducto = $dto->IdSetup;

            while (!$isExito) {

                $sku->Sku = 'PRD-' . strtoupper(Str::random(6));


                $existSku = $this->GetBySku($sku->Sku, $lang);

                if (!$existSku->IsSuccess) {

                    $respSku = $this->_repository->Create($sku, $lang);

                    if (!$respSku->IsSuccess) {
                        throw new Exception($respSku->Message);
                    }

                    $isExito = true;
                }
            }

            return $respSku;

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetBySku(string $sku, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetBySku($sku, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByProducto(int $idProducto, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetByProducto($idProducto, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
