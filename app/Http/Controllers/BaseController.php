<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use App\Domain\Entity\RespuestaEntity;

abstract class BaseController
{
    protected UsuarioService $_userService;
    protected TipoUsuarioService $_userTipoService;
    private array $translations = [
        "es" => [
            "token_not_sent" => "Token no enviado",
            "invalid_token_format" => "Formato de token inválido",
            "authorized" => "Autorizado",
            "no_permission" => "No tienes permisos para realizar esta acción"
        ],
        "en" => [
            "token_not_sent" => "Token not sent",
            "invalid_token_format" => "Invalid token format",
            "authorized" => "Authorized",
            "no_permission" => "You do not have permission to perform this action"
        ]
    ];

    public function __construct(UsuarioService $userService, TipoUsuarioService $userTipoService)
    {
        $this->_userService = $userService;
        $this->_userTipoService = $userTipoService;
    }


    protected function validarTokenHeader($tipoUsuario, $lang): RespuestaEntity
    {
        $tipoUsuario = strtoupper($tipoUsuario);
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return new RespuestaEntity($this->translations[$lang]['token_not_sent'], false, null);
        }

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return new RespuestaEntity($this->translations[$lang]['invalid_token_format'], false, null);
        }

        $token = $matches[1];

        $resp = $this->_userService->AuthorizationUser($token, $lang);
        if (!$resp->IsSuccess) {
            return $resp;
        }

        $tipoUserResp = $this->_userTipoService->GetByName($tipoUsuario, $lang);
        if (!$tipoUserResp->IsSuccess) {
            return $tipoUserResp;
        }

        if ($resp->Data->IdTipoUsuario == $tipoUserResp->Data->Id) {

            return new RespuestaEntity($this->translations[$lang]['authorized'], true, [
                "usuario" => $resp->Data,
                "token" => $token
            ]);
        }

        return new RespuestaEntity($this->translations[$lang]['no_permission'], false, null);
    }


    protected function validarTokenHeaderAll($lang): RespuestaEntity
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return new RespuestaEntity($this->translations[$lang]['token_not_sent'], false, null);
        }

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return new RespuestaEntity($this->translations[$lang]['invalid_token_format'], false, null);
        }

        $token = $matches[1];

        $resp = $this->_userService->AuthorizationUser($token, $lang);
        if (!$resp->IsSuccess) {
            return $resp;
        }

        return new RespuestaEntity($this->translations[$lang]['authorized'], true, [
            "usuario" => $resp->Data,
            "token" => $token
        ]);
    }
}
