<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\PaisEntity;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IPaisRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class PaisRepository implements IPaisRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "country_created" => "País creado correctamente",
            "list_retrieved" => "Lista obtenida correctamente",
            "country_found" => "País encontrado",
            "country_not_found" => "País no encontrado",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "country_created" => "Country created successfully",
            "list_retrieved" => "List retrieved successfully",
            "country_found" => "Country found",
            "country_not_found" => "Country not found",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "PAIS";
    }

    public function Create(PaisEntity $entity, string $lang): RespuestaEntity
    {
        try {

            return DB::transaction(function () use ($entity, $lang) {

                $id = DB::table($this->table)->insertGetId([
                    'nombre' => $entity->Nombre,
                ]);

                $entity->Id = $id;

                return new RespuestaEntity(
                    $this->translations[$lang]['country_created'] ?? "",
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

            $paises = $rows->map(function ($row) {

                $pais = new PaisEntity();
                $pais->Id = $row->id;
                $pais->Nombre = $row->nombre;

                return $pais;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? "",
                true,
                $paises
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
                    $this->translations[$lang]['country_not_found'] ?? "",
                    false,
                    null
                );
            }

            $pais = new PaisEntity();
            $pais->Id = $row->id;
            $pais->Nombre = $row->nombre;

            return new RespuestaEntity(
                $this->translations[$lang]['country_found'] ?? "",
                true,
                $pais
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