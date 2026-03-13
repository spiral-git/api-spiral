<?php

namespace App\Application\Services;

use App\Domain\Entity\LenguajeEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ILenguajeRepository;
use Exception;

class LenguajeService
{
    protected ILenguajeRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "language_exists" => "Ya existe el lenguaje"
        ],
        "en" => [
            "error" => "An error occurred",
            "language_exists" => "Language already exists"
        ]
    ];

    public function __construct(ILenguajeRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['language_exists'] ?? "", false, null);
            }

            $lenguaje = new LenguajeEntity();
            $lenguaje->Nombre = $nombre;

            return $this->_repository->Create($lenguaje, $lang);
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
