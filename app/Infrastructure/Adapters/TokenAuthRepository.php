<?php

namespace App\Infrastructure\Adapters;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TokenAuthEntity;
use App\Domain\Ports\ITokenAuthRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class TokenAuthRepository implements ITokenAuthRepository
{
    public string $table;

    private array $translations = [

        "es" => [
            "not_found" => "No encontrado",
            "found" => "Encontrado",
            "error" => "Ocurrió un error",
            "token_created" => "Token creado correctamente",
            "token_not_found" => "Token no encontrado",
            "token_deleted" => "Token eliminado correctamente",
            "tokens_deleted" => "Tokens eliminados correctamente"
        ],

        "en" => [
            "not_found" => "Not found",
            "found" => "Found",
            "error" => "An error occurred",
            "token_created" => "Token created successfully",
            "token_not_found" => "Token not found",
            "token_deleted" => "Token deleted successfully",
            "tokens_deleted" => "Tokens deleted successfully"
        ]

    ];

    public function __construct()
    {
        $this->table = "TOKENS_AUTH";
    }

    public function GetByToken(string $token, string $lang): RespuestaEntity
    {
        try {
            $row = DB::table($this->table)
                ->where("token", $token)
                ->first();

            if (!$row) {
                return new RespuestaEntity(
                    $this->translations[$lang]['not_found'] ?? "",
                    false,
                    null
                );
            }

            $tokenAuth = new TokenAuthEntity();
            $tokenAuth->Id = $row->id;
            $tokenAuth->IdUsuario = $row->id_usuario;
            $tokenAuth->Token = $row->token;

            return new RespuestaEntity(
                $this->translations[$lang]['found'] ?? "",
                true,
                $tokenAuth
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
    public function New(TokenAuthEntity $tokenAuth, string $lang): RespuestaEntity
    {
        try {

            $id = DB::table($this->table)->insertGetId([
                'token' => $tokenAuth->Token,
                'id_usuario' => $tokenAuth->IdUsuario
            ]);

            $tokenAuth->Id = $id;

            return new RespuestaEntity(
                $this->translations[$lang]['token_created'] ?? "",
                true,
                $tokenAuth
            );

        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Delete(string $token, string $lang): RespuestaEntity
    {
        try {

            $deleted = DB::table($this->table)
                ->where("token", $token)
                ->delete();

            if ($deleted === 0) {
                return new RespuestaEntity(

                    $this->translations[$lang]['token_not_found'] ?? "",
                    false,
                    null
                );
            }

            return new RespuestaEntity(
                $this->translations[$lang]['token_deleted'] ?? "",
                true,
                null
            );
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function DeleteAll(int $id_usuario, string $lang): RespuestaEntity
    {
        try {

            $deleted = DB::table($this->table)
                ->where("id_usuario", $id_usuario)
                ->delete();

            return new RespuestaEntity(
                $this->translations[$lang]['tokens_deleted'] ?? "",
                true,
                null
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
