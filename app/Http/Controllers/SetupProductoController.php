<?php

namespace App\Http\Controllers;

use App\Application\DTOs\Setup\SetupInputDto;
use App\Application\Services\ProductoSetupService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetupProductoController
{
    private ProductoSetupService $_service;

    public function __construct(ProductoSetupService $service)
    {
        $this->_service = $service;
    } 

    public function Create(Request $request)
    {
        
        $lang   = $request->input('lang') ?? "es";
        $amount   = $request->input('amount') ?? 0;
        $idTipoSetup   = $request->input('idTipoSetup') ?? 0;

        $dto = new SetupInputDto();
        $dto->Amount = $amount;
        $dto->IdTipoSetup = $idTipoSetup;


        $respuesta = $this->_service->Create($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }

}
