<?php

namespace App\Http\Controllers;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Services\ImagenProductoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImagenProductoController
{
    private ImagenProductoService $_service;

    public function __construct(ImagenProductoService $service)
    {
        $this->_service = $service;
    } 

    public function Create(Request $request)
    {
        
        $lang   = $request->input('lang') ?? "es";
        $idProducto   = $request->input('idProducto') ?? 0;
        $ruta   = $request->input('ruta') ?? '';

        $dto = new ImagenProductoInputDto();
        $dto->Ruta = $ruta;
        $dto->IdProducto = $idProducto;

        $respuesta = $this->_service->Create($dto,$lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
