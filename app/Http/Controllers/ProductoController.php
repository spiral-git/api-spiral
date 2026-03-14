<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Producto\ProductoInputDto;
use App\Application\Services\ProductoService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductoController extends BaseController
{
    private UsuarioService $_usuarioService;
    private ProductoService $_service;

    private TipoUsuarioService $_tipoUsuarioService;


    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService, ProductoService $service)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {
        $dto = new ProductoInputDto();
        $dto->IdTipoProducto = $request->input('idTipoProducto') ?? 0;
        $dto->IdTipoPago     = $request->input('idTipoPago') ?? 0;
        $dto->IdLenguaje     = $request->input('idLenguaje') ?? 0;
        $dto->Nombre         = $request->input('nombre') ?? "";
        $dto->Descripcion    = $request->input('descripcion') ?? "";
        $lang   = $request->input('lang') ?? "es";

        $resp = $this->validarTokenHeaderOR(["ADMINISTRADOR", "SOCIO"], $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];

        $dto->IdOwner = $user->Id;

        $respuesta = $this->_service->crearProducto($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

    

    
}
