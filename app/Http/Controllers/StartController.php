<?php

namespace App\Http\Controllers;

use App\Application\Services\StartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
class StartController extends Controller
{
    private StartService $service;

    public function __construct(StartService $service)
    {
        $this->service = $service;
    }

    public function Start()
    {
        $respuesta = $this->service->Start();
        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
