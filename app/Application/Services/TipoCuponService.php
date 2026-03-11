<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoCuponEntity;
use App\Domain\Ports\ITipoCuponRepository;
use Exception;
use Illuminate\Support\Facades\Lang;

class TipoCuponService
{
    protected ITipoCuponRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "coupon_type_exists" => "Ya existe el tipo de cupón"
        ],
        "en" => [
            "error" => "An error occurred",
            "coupon_type_exists" => "Coupon type already exists"
        ]
    ];
    public function __construct(ITipoCuponRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['coupon_type_exists'] ?? "", false, null);
            }

            $tipoCupon = new TipoCuponEntity();
            $tipoCupon->Nombre = $nombre;

            return $this->_repository->Create($tipoCupon, $lang);
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
