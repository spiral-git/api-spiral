<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\LenguajeEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\ILenguajeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class LenguajeRepository implements ILenguajeRepository
{
    public string $table;

    private array $translations = [
        "es" => [
            "language_created" => "Lenguaje creado correctamente",
            "list_retrieved" => "Lista obtenida correctamente",
            "language_found" => "Lenguaje encontrado",
            "language_not_found" => "Lenguaje no encontrado",
            "error" => "Ocurrió un error"
        ],
        "en" => [
            "language_created" => "Language created successfully",
            "list_retrieved" => "List retrieved successfully",
            "language_found" => "Language found",
            "language_not_found" => "Language not found",
            "error" => "An error occurred"
        ]
    ];

    public function __construct()
    {
        $this->table = "LENGUAJE";
    }

    public function Create(LenguajeEntity $entity, string $lang): RespuestaEntity
    {
        try {
            return DB::transaction(function () use ($entity, $lang) {
                $id = DB::table($this->table)->insertGetId([
                    'nombre' => $entity->Nombre,
                ]);

                $entity->Id = $id;

                return new RespuestaEntity(
                    $this->translations[$lang]['language_created'] ?? "",
                    true,
                    $entity
                );
            });
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
            $rows = DB::table($this->table)
                ->orderBy('id')
                ->get();

            $entities = $rows->map(function ($row) {
                $entity = new LenguajeEntity();
                $entity->Id = $row->id;
                $entity->Nombre = $row->nombre;
                return $entity;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? "",
                true,
                $entities
            );
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
            $row = DB::table($this->table)
                ->where("nombre", $name)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['language_not_found'] ?? "",
                    false,
                    null
                );
            }

            $entity = new LenguajeEntity();
            $entity->Id = $row->id;
            $entity->Nombre = $row->nombre;

            return new RespuestaEntity(
                $this->translations[$lang]['language_found'] ?? "",
                true,
                $entity
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}