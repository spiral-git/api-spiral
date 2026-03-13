<?php

namespace App\Http\Controllers;

use App\Application\Services\StartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

class StartController extends Controller
{
    private StartService $service;

    public function __construct(StartService $service)
    {
        $this->service = $service;
    }

    #[OA\Get(
        path: '/start',
        summary: 'Verifica que la API esté funcionando',
        description: 'Devuelve un objeto indicando si la API está operativa.',
        tags: ['Start'],
        responses: [
            new OA\Response(response: 200, description: 'API operativa correctamente'),
            new OA\Response(response: 400, description: 'Error en la petición'),
        ]
    )]
    public function Start()
    {
        $respuesta = $this->service->Start();
        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
