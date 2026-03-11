<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\UsuarioEntity;
use App\Domain\Ports\IUsuarioRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class UsuarioRepository implements IUsuarioRepository
{

    public string $table;
    private array $translations = [
        "es" => [
            "created" => "Usuario creado correctamente",
            "list" => "Lista obtenida correctamente",
            "found" => "Encontrado",
            "not_found" => "No encontrado",
            "error" => "Ocurrió un error"
        ],

        "en" => [
            "created" => "User created successfully",
            "list" => "List retrieved successfully",
            "found" => "Found",
            "not_found" => "Not found",
            "error" => "An error occurred"
        ]
    ];

    public function __construct()
    {
        $this->table = "USUARIO";
    }


    public function Create(UsuarioEntity $usuario, string $lang): RespuestaEntity
    {
        try {
            $id = DB::table($this->table)->insertGetId([
                'nombres' => $usuario->Nombres,
                'correo' => $usuario->Correo,
                'id_tipo_usuario' => $usuario->IdTipoUsuario,
                'apellidos' => $usuario->Apellidos,
                'password' => $usuario->Password,
                'imagen' => $usuario->Imagen,
                'telefono' => $usuario->Telefono,
                'status' => $usuario->Status
            ]);

            $usuario->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['created'] ?? "",
                true,
                $usuario
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

            $usuarios = $rows->map(function ($row) {
                $user = new UsuarioEntity();
                $user->Apellidos = $row->apellidos;
                $user->Correo = $row->correo;
                $user->Id = $row->id;
                $user->IdTipoUsuario = $row->id_tipo_usuario;
                $user->Imagen = $row->imagen;
                $user->Nombres = $row->nombres;
                $user->Password = $row->password;
                $user->Status = $row->status;
                $user->Telefono = $row->telefono;
                return $user;
            });



            return new RespuestaEntity(
                $this->translations[$lang]['list'] ?? "",
                true,
                $usuarios
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

            $user = new UsuarioEntity();
            $user->Apellidos = $row->apellidos;
            $user->Correo = $row->correo;
            $user->Id = $row->id;
            $user->IdTipoUsuario = $row->id_tipo_usuario;
            $user->Imagen = $row->imagen;
            $user->Nombres = $row->nombres;
            $user->Password = $row->password;
            $user->Status = $row->status;
            $user->Telefono = $row->telefono;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $user
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }


    public function GetByCorreo(string $correo, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where("correo", $correo)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $user = new UsuarioEntity();
            $user->Apellidos = $row->apellidos;
            $user->Correo = $row->correo;
            $user->Id = $row->id;
            $user->IdTipoUsuario = $row->id_tipo_usuario;
            $user->Imagen = $row->imagen;
            $user->Nombres = $row->nombres;
            $user->Password = $row->password;
            $user->Status = $row->status;
            $user->Telefono = $row->telefono;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $user
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                false,
                null
            );
        }
    }


}
