<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Producto\ProductoCotizableDto;
use App\Application\Services\ProductoCotizableService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductoCotizacionController extends BaseController
{
    private UsuarioService $_usuarioService;
    private ProductoCotizableService $_service;

    private TipoUsuarioService $_tipoUsuarioService;


    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService, ProductoCotizableService $service)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {
        $dto = new ProductoCotizableDto();
        $dto->IdProducto = $request->input('idProducto') ?? 0;
        $dto->MaximoRecursos = $request->input('maximoRecursos') ?? 0;
        $dto->IdTipoSetup = $request->input('idTipoSetup') ?? 0;
        $dto->AmountSetup = $request->input('amountSetup') ?? 0;
        $lang = $request->input('lang') ?? "es";

        $resp = $this->validarTokenHeaderOR(["ADMINISTRADOR", "SOCIO"], $lang);

        if (!$resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $resp->Data['usuario'];

        $ownerId = $user->Id;

        $respuesta = $this->_service->Created($dto, $lang, $ownerId);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
    public function GetById(Request $request)
    {
        $lang = $request->input('lang') ?? 'es';
        $id = $request->input('id') ?? 0;


        $resp = $this->validarTokenHeaderOR(['ADMINISTRADOR', 'SOCIO'], $lang);

        if (! $resp->IsSuccess) {
            return response()->json(
                $resp,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $respuesta = $this->_service->GetById($lang, $id);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

}
