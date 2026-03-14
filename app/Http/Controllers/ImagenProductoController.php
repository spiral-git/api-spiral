<?php

namespace App\Http\Controllers;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Services\ImagenProductoService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class ImagenProductoController extends BaseController
{
    private ImagenProductoService $_service;
    private UsuarioService $_usuarioService;
    private TipoUsuarioService $_tipoUsuarioService;

    public function __construct(ImagenProductoService $service, UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService)
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

        $imagenes = Arr::wrap($request->input('imagenes'));
        $imagenes = array_filter($imagenes, fn($i) => is_string($i) && trim($i) !== '');

        $dto = new ImagenProductoInputDto();
        $dto->Imagenes = $imagenes;
        $dto->IdProducto = $idProducto;

        $resp = $this->validarTokenHeaderOR(["ADMINISTRADOR", "SOCIO"], $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];

        $ownerId = $user->ID;

        $respuesta = $this->_service->Create($dto, $lang, $ownerId);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
