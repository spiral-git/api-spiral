<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Usuario\UsuarioInputDto;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsuarioController extends BaseController
{
    private UsuarioService $_service;
    private TipoUsuarioService $_tipoUsuarioService;


    public function __construct(UsuarioService $service, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_service = $service;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        parent::__construct($this->_service, $this->_tipoUsuarioService);
    }

    public function login(Request $request)
    {
        $lang = $request->input('lang') ?? "es";
        $correo = $request->input('correo') ?? "";
        $password = $request->input('password') ?? "";

        $respuesta = $this->_service->Login($correo, $password, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }



    public function logout(Request $request)
    {
        $lang = $request->input('lang') ?? "es";
        $resp = $this->validarTokenHeaderAll($lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];
        $token = $resp->Data['token'];

        $respuesta = $this->_service->ClosedSesion($token, $lang);
        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    public function logoutAll(Request $request)
    {
        $lang = $request->input('lang') ?? "es";
        $resp = $this->validarTokenHeaderAll($lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];
        $token = $resp->Data['token'];

        $respuesta = $this->_service->ClosedAllSesion($user->Id, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    public function crearUsuario(Request $request)
    {

        $dto = new UsuarioInputDto();
        $dto->Nombres = $request->input('nombres') ?? "";
        $dto->Apellidos = $request->input('apellidos') ?? "";
        $dto->Correo = $request->input('correo') ?? "";
        $dto->Password = $request->input('password') ?? "";
        $dto->Telefono = $request->input('telefono') ?? "";
        $dto->Imagen = $request->input('imagen') ?? "";
        $lang = $request->input('lang') ?? "es";

        $respuesta = $this->_service->Crear(
            $dto,
            "CLIENTE",
            $lang
        );

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    public function crearSocio(Request $request)
    {

        $dto = new UsuarioInputDto();
        $dto->Nombres = $request->input('nombres') ?? "";
        $dto->Apellidos = $request->input('apellidos') ?? "";
        $dto->Correo = $request->input('correo') ?? "";
        $dto->Password = $request->input('password') ?? "";
        $dto->Telefono = $request->input('telefono') ?? "";
        $dto->Imagen = $request->input('imagen') ?? "";
        $lang = $request->input('lang') ?? "es";

        $resp = $this->validarTokenHeader("ADMINISTRADOR", $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $respuesta = $this->_service->Crear(
            $dto,
            "SOCIO",
            $lang
        );

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

}
