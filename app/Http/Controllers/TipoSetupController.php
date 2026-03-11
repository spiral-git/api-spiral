<?php

namespace App\Http\Controllers;

use App\Application\Services\TipoSetupService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoSetupController
{
    private TipoSetupService $service;

    public function __construct(TipoSetupService $service)
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
