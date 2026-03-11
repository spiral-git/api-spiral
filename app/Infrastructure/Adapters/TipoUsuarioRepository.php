<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoUsuarioEntity;
use App\Domain\Ports\ITipoUsuarioRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class TipoUsuarioRepository implements ITipoUsuarioRepository
{

    public string $table;
    private array $translations = [

        "es" => [
            "user_type_created" => "Tipo de usuario creado correctamente",
            "list_retrieved" => "Lista obtenida correctamente",
            "not_found" => "No encontrado",
            "found" => "Encontrado",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "user_type_created" => "User type created successfully",
            "list_retrieved" => "List retrieved successfully",
            "not_found" => "Not found",
            "found" => "Found",
            "error" => "An error occurred"
        ]

    ];


    public function __construct()
    {
        $this->table = "TIPO_USUARIO";
    }

    public function Create(TipoUsuarioEntity $tipoUsuario, string $lang): RespuestaEntity
    {
        try {
          
                $id = DB::table($this->table)->insertGetId([
                    'nombre' => $tipoUsuario->Nombre,
                ]);

                $tipoUsuario->Id = $id;

                return new RespuestaEntity(
                    $this->translations[$lang]['user_type_created'] ?? "",
                    true,
                    $tipoUsuario
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
                $tipo_user = new TipoUsuarioEntity();
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

            $tipo_user = new TipoUsuarioEntity();
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
