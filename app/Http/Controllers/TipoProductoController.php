<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoProductoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoProductoController
{
    private TipoProductoService $service;

    public function __construct(TipoProductoService $service)
    {
        $this->service = $service;
    }

    public function GetAll(string $lang)
    {
        $respuesta = $this->service->GetAll($lang);
        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK  : Response::HTTP_BAD_REQUEST
        );
    }
}
