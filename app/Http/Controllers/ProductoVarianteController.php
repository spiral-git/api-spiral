<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Producto\ProductoVarianteInputDto;
use App\Application\Services\ProductoVarianteService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductoVarianteController extends BaseController
{
    private UsuarioService $_usuarioService;

    private ProductoVarianteService $_service;

    private TipoUsuarioService $_tipoUsuarioService;

    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService, ProductoVarianteService $service)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }

    public function Create(Request $request)
    {
        $dto = new ProductoVarianteInputDto;
        $dto->IdProducto = $request->input('idProducto') ?? 0;
        $dto->MaximoRecursos = $request->input('maximoRecursos') ?? 0;
        $dto->IdTipoSetup = $request->input('idTipoSetup') ?? 0;
        $dto->AmountSetup = $request->input('amountSetup') ?? 0;
        $dto->Precio = $request->input('precio') ?? 0;
        $dto->IdTipoDescuento = $request->input('idTipoDescuento') ?? 0;
        $dto->Descuento = $request->input('descuento') ?? 0;
        $dto->Nombre = $request->input('nombre') ?? '';

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
