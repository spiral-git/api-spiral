<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoPagoEntity;
use App\Domain\Ports\ITipoPagoRepository;
use Exception;

class TipoPagoService
{
    protected ITipoPagoRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "payment_type_exists" => "Ya existe el tipo de pago"
        ],
        "en" => [
            "error" => "An error occurred",
            "payment_type_exists" => "Payment type already exists"
        ]
    ];
    public function __construct(ITipoPagoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['payment_type_exists'] ?? "", false, null);
            }

            $tipoPago = new TipoPagoEntity();
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
}
