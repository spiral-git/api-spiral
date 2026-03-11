<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Sku\SkuInputDto;
use App\Application\Services\SkuService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkuProductoController
{
    private SkuService $_service;

    public function __construct(SkuService $service)
    {
        $this->_service = $service;
    } 

    public function Create(Request $request)
    {
        
        $lang   = $request->input('lang') ?? "es";
        $idProducto   = $request->input('idProducto') ?? 0;
        $maximoRecursos   = $request->input('maximoRecursos') ?? 0;
        $idSetup   = $request->input('idSetup') ?? 0;

        $dto = new SkuInputDto();
        $dto->IdProducto = $idProducto;
        $dto->IdSetup = $idSetup;
        $dto->MaximoRecursos = $maximoRecursos;


        $respuesta = $this->_service->crearSku($dto,$lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }




}