<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoProductoEntity;
use App\Domain\Ports\ITipoProductoRepository;
use Exception;

class TipoProductoService
{
    protected ITipoProductoRepository $_repository;
    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "product_type_exists" => "Ya existe el tipo de producto"
        ],
        "en" => [
            "error" => "An error occurred",
            "product_type_exists" => "Product type already exists"
        ]
    ];
    public function __construct(ITipoProductoRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['product_type_exists'], false, null);
            }

            $tipoProducto = new TipoProductoEntity();
            $tipoProducto->Nombre = $nombre;

            return $this->_repository->Create($tipoProducto, $lang);
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
