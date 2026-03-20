<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Producto\DetallePlanInputDto;
use App\Application\Services\PlanDetalleService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetallePlanController extends BaseController
{
     private UsuarioService $_usuarioService;

    private TipoUsuarioService $_tipoUsuarioService;

    private PlanDetalleService $_service;

    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService, PlanDetalleService $service)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {
        $dto = new DetallePlanInputDto;
        $dto->IdProductoPlan = $request->input('idPlan') ?? 0;
        $dto->Detalle = $request->input('detalle') ?? "";
        $lang = $request->input('lang') ?? 'es';   

        $resp = $this->validarTokenHeaderOR(['ADMINISTRADOR', 'SOCIO'], $lang);

        if (! $resp->IsSuccess) {
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

    
}
