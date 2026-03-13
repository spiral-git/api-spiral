<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoSetupEntity;
use App\Domain\Ports\ITipoSetupRepository;
use Exception;

class TipoSetupService
{
    protected ITipoSetupRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "setup_type_exists" => "Ya existe el tipo de setup"
        ],
        "en" => [
            "error" => "An error occurred",
            "setup_type_exists" => "Setup type already exists"
        ]
    ];
    public function __construct(ITipoSetupRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['setup_type_exists'], false, null);
            }

            $tipoSetup = new TipoSetupEntity();
            $tipoSetup->Nombre = $nombre;

            return $this->_repository->Create($tipoSetup, $lang);
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
