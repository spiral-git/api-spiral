<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoPagoService;
use Symfony\Component\HttpFoundation\Response;

class TipoPagoController
{
    private TipoPagoService $service;

    public function __construct(TipoPagoService $service)
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
