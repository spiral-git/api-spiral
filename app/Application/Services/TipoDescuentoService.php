<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoDescuentoEntity;
use App\Domain\Ports\ITipoDescuentoRepository;
use Exception;

class TipoDescuentoService
{
     protected ITipoDescuentoRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "discount_type_exists" => "Ya existe el tipo de descuento"
        ],
        "en" => [
            "error" => "An error occurred",
            "discount_type_exists" => "Discount type already exists"
        ]
    ];
    public function __construct(ITipoDescuentoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['discount_type_exists'] ?? "", false, null);
            }

            $tipoPago = new TipoDescuentoEntity();
            $tipoPago->Nombre = $nombre;

            return $this->_repository->Create($tipoPago, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetAll(string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetAll($lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByName(string $name, string $lang): RespuestaEntity
    {
        try {
            $name = strtoupper($name);
            return $this->_repository->GetByName($name, $lang);
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
