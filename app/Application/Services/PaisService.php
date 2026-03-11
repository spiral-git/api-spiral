<?php

namespace App\Application\Services;

use App\Domain\Entity\PaisEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IPaisRepository;
use Exception;

class PaisService
{
    protected IPaisRepository $_repository;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "country_exists" => "Ya existe el país"
        ],
        "en" => [
            "error" => "An error occurred",
            "country_exists" => "Country already exists"
        ]
    ];
    public function __construct(IPaisRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['country_exists'] ?? "", false, null);
            }

            $pais = new PaisEntity();
            $pais->Nombre = $nombre;

            return $this->_repository->Create($pais, $lang);
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
