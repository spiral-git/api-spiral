<?php

namespace App\Http\Controllers;

use App\Application\DTOs\PaisProducto\PaisProductoInputDto;
use App\Application\Services\PaisProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $paises = array_map('intval', Arr::wrap($request->input('paises')));
        $paises = array_filter($paises, fn($c) => $c > 0);
        $sku = $request->input('sku') ?? '';

        $dto = new PaisProductoInputDto();
        $dto->Paises = $paises;
        $dto->SkuProducto = $sku;

        $respuesta = $this->_service->Create($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

}
