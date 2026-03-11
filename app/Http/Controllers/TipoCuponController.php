<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoCuponService;
use Symfony\Component\HttpFoundation\Response;

class TipoCuponController
{
    private TipoCuponService $service;

    public function __construct(TipoCuponService $service)
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
