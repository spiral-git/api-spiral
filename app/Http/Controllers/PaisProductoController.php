<?php

namespace App\Http\Controllers;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Services\PaisProductoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaisProductoController
{
    private PaisProductoService $_service;

    public function __construct(PaisProductoService $service)
    {
        $this->_service = $service;
    }

    public function Create(Request $request)
    {

        $lang = $request->input('lang') ?? "es";
        $idPais = $request->input('idPais') ?? 0;
        $sku = $request->input('sku') ?? '';

        $dto = new PaisProductoInputDto();
        $dto->IdPais = $idPais;
        $dto->SkuProducto = $sku;

        $respuesta = $this->_service->Create($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

}
