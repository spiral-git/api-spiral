<?php

namespace App\Http\Controllers;

use App\Application\Services\CategoriaService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriaController extends BaseController
{
    private UsuarioService $_usuarioService;
    private CategoriaService $_service;
    private TipoUsuarioService $_tipoUsuarioService;


    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService, CategoriaService $service)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function GetAll(int $idlenguaje, string $lang)
    {
        $respuesta = $this->_service->GetAll($idlenguaje, $lang);
        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    public function Create(Request $request)
    {

        $name = $request->input('name') ?? "";
        $idLenguaje = $request->input('idLenguaje') ?? 0;
        $lang = $request->input('lang') ?? "es";

        $resp = $this->validarTokenHeader("ADMINISTRADOR", $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $respuesta = $this->_service->Create($name, $idLenguaje, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    public function Update(Request $request)
    {
        $name = $request->input('name') ?? "";
        $idLenguaje = $request->input('idLenguaje') ?? 0;
        $id = $request->input('id') ?? 0;
        $status = $request->input('status') ?? true;
        $lang = $request->input('lang') ?? "es";

        $resp = $this->validarTokenHeader("ADMINISTRADOR", $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $respuesta = $this->_service->Update($name, $idLenguaje, $id, $status, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
