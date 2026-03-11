<?php

namespace App\Http\Controllers;

use App\Application\Services\LenguajeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LenguajeController
{
    private LenguajeService $service;

    public function __construct(LenguajeService $service)
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
