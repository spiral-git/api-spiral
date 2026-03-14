<?php

namespace App\Application\Services;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Application\Mappers\MapperUsuario;
use App\Application\Validations\UsuarioValidations;
use App\Domain\Entity\RespuestaEntity;
use App\Domain\Ports\IUsuarioRepository;
use Exception;

class UsuarioService
{
    protected IUsuarioRepository $_repository;
    protected TokenAuthService $_tokenAuthService;
    protected TipoUsuarioService $_tipoUsuarioService;

    private array $translations = [
        "es" => [
            "error" => "Ocurrió un error",
            "user_exists" => "El usuario ya existe, inicia sesión.",
            "user_created_login" => "Usuario Creado Exitosamente. Inicia Sesión",
            "user_created" => "Usuario Creado Exitosamente.",
            "invalid_credentials" => "Credenciales incorrectas",
            "login_success" => "Inicio de sesión correcto",
            "logout_success" => "Sesión Finalizada",
            "all_sessions_ended" => "Sesiones Finalizadas",
            "token_invalid" => "Token inválido",
            "token_valid" => "Token válido"
        ],
        "en" => [
            "error" => "An error occurred",
            "user_exists" => "User already exists, please login.",
            "user_created_login" => "User created successfully. Please login.",
            "user_created" => "User created successfully.",
            "invalid_credentials" => "Incorrect credentials",
            "login_success" => "Login successful",
            "logout_success" => "Session ended",
            "all_sessions_ended" => "All sessions ended",
            "token_invalid" => "Invalid token",
            "token_valid" => "Valid token"
        ]
    ];

    public function __construct(IUsuarioRepository $repository, TokenAuthService $tokenAuthService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_repository = $repository;
        $this->_tokenAuthService = $tokenAuthService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
    }

    public function Crear(UsuarioInputDto $dto, string $tipoUsuarioName, string $lang): RespuestaEntity
    {
        try {

            $validations = UsuarioValidations::validar($dto, $lang);

            if (!$validations->IsSuccess) {
                return $validations;
            }


            $respExist = $this->GetByMail($dto->Correo, $lang);
            if ($respExist->IsSuccess) {
                return new RespuestaEntity($this->translations[$lang]['user_exists'], false, null);
            }

            $usuarioEntity = MapperUsuario::inputDtoToEntity($dto);
            $usuarioEntity->Password = $this->EncriptarPassword($usuarioEntity->Password);
            $usuarioEntity->Status = true;
            $usuarioEntity->Correo = strtoupper($usuarioEntity->Correo);
            $tipoUsuarioName = strtoupper($tipoUsuarioName);

            $tipoUsuario = $this->_tipoUsuarioService->GetByName($tipoUsuarioName, $lang);

            $usuarioEntity->IdTipoUsuario = $tipoUsuario->Data->Id;

            $resp = $this->_repository->Create($usuarioEntity, $lang);

            if ($resp->IsSuccess) {

                $respToken = $this->_tokenAuthService->New($resp->Data->Id, $lang);

                if (!$respToken->IsSuccess) {
                    return new RespuestaEntity($this->translations[$lang]['user_created_login'], true, null);
                } else {
                    return new RespuestaEntity($this->translations[$lang]['user_created'], true, ["usuario" => $resp->Data, "token" => $respToken->Data->Token]);
                }
            }

            return $resp;
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function Login(string $correo, string $password, string $lang): RespuestaEntity
    {
        try {

            $usuarioResp = $this->GetByMail($correo, $lang);
            if (!$usuarioResp->IsSuccess) {
                return $usuarioResp;
            }

            $esValida = $this->ValidarPassword(
                $password,
                $usuarioResp->Data->Password
            );

            if (!$esValida) {
                return new RespuestaEntity($this->translations[$lang]['invalid_credentials'], false, null);
            }

            $respToken = $this->_tokenAuthService->New($usuarioResp->Data->Id, $lang);

            if (!$respToken->IsSuccess) {
                return $respToken;
            }

            unset($usuarioResp->Data->Password);
            
            return new RespuestaEntity($this->translations[$lang]['login_success'], true, ["usuario" => $usuarioResp->Data, "token" => $respToken->Data->Token]);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }


    public function Logout(string $token, string $lang): RespuestaEntity
    {
        try {
            $resp = $this->_tokenAuthService->Delete($token, $lang);
            if (!$resp->IsSuccess) {
                return $resp;
            }
            return new RespuestaEntity($this->translations[$lang]['logout_success'], true, null);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function ClosedAllSesion(int $userId, string $lang): RespuestaEntity
    {
        try {
            $resp = $this->_tokenAuthService->DeleteAll($userId, $lang);
            if (!$resp->IsSuccess) {
                return $resp;
            }
            return new RespuestaEntity( $this->translations[$lang]['all_sessions_ended'], true, null);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function ClosedSesion(string $token, string $lang): RespuestaEntity
    {
        try {
            $resp = $this->_tokenAuthService->Delete($token, $lang);
            if (!$resp->IsSuccess) {
                return $resp;
            }
            return new RespuestaEntity($this->translations[$lang]['logout_success'], true, null);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function AuthorizationUser(string $token, string $lang): RespuestaEntity
    {
        try {
            $resp = $this->_tokenAuthService->ValidateToken($token, $lang);

            if (!$resp->IsSuccess) {
                return new RespuestaEntity( $this->translations[$lang]['token_invalid'], false, null);
            }

            $userResp = $this->GetById($resp->Data->IdUsuario, $lang);
            if (!$userResp->IsSuccess) {
                return new RespuestaEntity( $this->translations[$lang]['token_invalid'], false, null);
            }

            return new RespuestaEntity($this->translations[$lang]['token_valid'], true, $userResp->Data);
        } catch (Exception $e) {
            return new RespuestaEntity(
                $this->translations[$lang]['error'] ?? "",
                false,
                null
            );
        }
    }

    public function GetByMail(string $mail, string $lang): RespuestaEntity
    {
        try {
            $mail = strtoupper($mail);
            return $this->_repository->GetByCorreo($mail, $lang);
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


    private function EncriptarPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }


    private function ValidarPassword(string $passwordPlano, string $passwordHash): bool
    {
        return password_verify($passwordPlano, $passwordHash);
    }
}
