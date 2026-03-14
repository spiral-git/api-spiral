<?php

namespace App\Http\Controllers;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Services\ProductoCategoriaService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;


class CategoriaProductoController extends BaseController
{
    private ProductoCategoriaService $_service;
    private UsuarioService $_usuarioService;
    private TipoUsuarioService $_tipoUsuarioService;

    public function __construct(ProductoCategoriaService $service, UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_service = $service;
        
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {

        $lang = $request->input('lang') ?? "es";
        $idProducto = $request->input('idProducto') ?? 0;
        $categorias = array_map('intval', Arr::wrap($request->input('categorias')));
        $categorias = array_filter($categorias, fn($c) => $c > 0);

        $dto = new CategoriaProductoDto();
        $dto->Categorias = $categorias;
        $dto->IdProducto = $idProducto;

        $resp = $this->validarTokenHeaderOR(["ADMINISTRADOR", "SOCIO"], $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];

        $ownerId = $user->Id;

        $respuesta = $this->_service->Create($dto, $lang, $ownerId);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
