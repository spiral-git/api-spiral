<?php

namespace App\Http\Controllers;

use App\Application\Services\PaisService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaisController
{
    private PaisService $service;

    public function __construct(PaisService $service)
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
