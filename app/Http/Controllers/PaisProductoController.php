<?php

namespace App\Http\Controllers;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Services\PaisProductoService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class PaisProductoController extends BaseController
{
    private PaisProductoService $_service;
    private UsuarioService $_usuarioService;
    private TipoUsuarioService $_tipoUsuarioService;

    public function __construct(PaisProductoService $service, UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_service = $service;
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {

        $lang = $request->input('lang') ?? "es";
        $paises = array_map('intval', Arr::wrap($request->input('paises')));
        $paises = array_filter($paises, fn($c) => $c > 0);
        $sku = $request->input('sku') ?? '';

        $dto = new PaisProductoInputDto();
        $dto->Paises = $paises;
        $dto->SkuProducto = $sku;

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
