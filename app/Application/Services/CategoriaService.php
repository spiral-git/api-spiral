<?php

namespace App\Application\Services;

use App\Domain\Entity\CategoriaEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ICategoriaRepository;
use Exception;

class CategoriaService
{
    protected ICategoriaRepository $_repository;
    private array $translations = [
        "es" => [
            "category_exists" => "Ya existe la categoría",
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "category_exists" => "Category already exists",
            "error" => "An error occurred"
        ]
    ];

    public function __construct(ICategoriaRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $name, int $idLenguaje, string $lang): RespuestaEntity
    {
        try {

            $name = strtoupper($name);
            $resp = $this->GetByName($name, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['category_exists'] ?? "", false, null);
            }

            $categoria = new CategoriaEntity();
            $categoria->Nombre = $name;
            $categoria->IdLenguaje = $idLenguaje;
            $categoria->Status = true;

            return $this->_repository->Create($categoria, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Update(string $name, int $idLenguaje, int $id, $status, string $lang): RespuestaEntity
    {

        try {

            $name = strtoupper($name);
            $resp = $this->GetByName($name, $lang);

            if ($resp->IsSuccess && $resp->Data->Id !== $id) {
                return new RespuestaEntity($this->translations[$lang]['category_exists'] ?? "", false, null);
            }

            $categoria = new CategoriaEntity();
            $categoria->Nombre = $name;
            $categoria->IdLenguaje = $idLenguaje;
            $categoria->Id = $id;
            $categoria->Status = $status;


            return $this->_repository->Update($categoria, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetAll(int $idLenguaje, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetAll($idLenguaje, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByName(string $nombre, string $lang): RespuestaEntity
    {
        try {
            return $this->_repository->GetByName($nombre, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
