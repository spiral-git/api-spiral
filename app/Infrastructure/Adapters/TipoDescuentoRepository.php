<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoDescuentoEntity;
use App\Domain\Ports\ITipoDescuentoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class TipoDescuentoRepository implements ITipoDescuentoRepository
{
    
     public string $table;

    private array $translations = [

        "es" => [
            "descuent_type_created" => "Tipo de descuento creado correctamente",
            "list_retrieved" => "Lista obtenida correctamente",
            "not_found" => "No encontrado",
            "found" => "Encontrado",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "descuent_type_created" => "Discount type created successfully",
            "list_retrieved" => "List retrieved successfully",
            "not_found" => "Not found",
            "found" => "Found",
            "error" => "An error occurred"
        ]

    ];

    public function __construct()
    {
        $this->table = "TIPO_DESCUENTO";
    }

    public function Create(TipoDescuentoEntity $entity, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'nombre' => $entity->Nombre,
            ]);

            $entity->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['descuent_type_created'] ?? "",
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

    public function GetAll(string $lang): RespuestaEntity
    {
        try {
            $rows = DB::table($this->table)
                ->orderBy('id')
                ->get();

            $tipoUsuarios = $rows->map(function ($row) {
                $tipo_user = new TipoDescuentoEntity();
                $tipo_user->Id = $row->id;
                $tipo_user->Nombre = $row->nombre;
                return $tipo_user;
            });

            return new RespuestaEntity(
                $this->translations[$lang]['list_retrieved'] ?? "",
                true,
                $tipoUsuarios
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
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $tipo_user = new TipoDescuentoEntity();
            $tipo_user->Id = $row->id;
            $tipo_user->Nombre = $row->nombre;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $tipo_user
            );

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
            $row = DB::table($this->table)
                ->where("id", $id)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $tipo_user = new TipoDescuentoEntity();
            $tipo_user->Id = $row->id;
            $tipo_user->Nombre = $row->nombre;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $tipo_user
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
