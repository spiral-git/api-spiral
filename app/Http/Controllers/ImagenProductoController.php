<?php

namespace App\Http\Controllers;

use App\Application\DTOs\ImagenProducto\ImagenProductoInputDto;
use App\Application\Services\ImagenProductoService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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

        $lang = $request->input('lang') ?? "es";
        $idProducto = $request->input('idProducto') ?? 0;

        $imagenes = Arr::wrap($request->input('imagenes'));
        $imagenes = array_filter($imagenes, fn($i) => is_string($i) && trim($i) !== '');

        $dto = new ImagenProductoInputDto();
        $dto->Imagenes = $imagenes;
        $dto->IdProducto = $idProducto;

        $respuesta = $this->_service->Create($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
