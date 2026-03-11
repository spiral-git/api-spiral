<?php

namespace App\Application\Services;

use App\Domain\Entity\RespuestaEntity;
use App\Domain\Entity\TipoUsuarioEntity;
use App\Domain\Ports\ITipoUsuarioRepository;
use Exception;

class TipoUsuarioService
{

    protected ITipoUsuarioRepository $_usuarioRepository;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "user_type_exists" => "Ya existe el tipo de usuario"
        ],
        "en" => [
            "error" => "An error occurred",
            "user_type_exists" => "User type already exists"
        ]
    ];

    public function __construct(ITipoUsuarioRepository $usuarioRepository)
    {
        $this->_usuarioRepository = $usuarioRepository;
    }

    public function Create(string $nombre, string $lang): RespuestaEntity
    {

        try {

            $nombre = strtoupper($nombre);
            $resp = $this->GetByName($nombre, $lang);

            if ($resp->IsSuccess) {
                return new RespuestaEntity("Ya existe el tipo de usuario", false, null);
            }

            $tipoUsuario = new TipoUsuarioEntity();
            $tipoUsuario->Nombre = $nombre;

            return $this->_usuarioRepository->Create($tipoUsuario, $lang);
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
            return $this->_usuarioRepository->GetAll($lang);
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
            return $this->_usuarioRepository->GetByName($name, $lang);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }
}
