<?php

namespace App\Http\Controllers;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Services\ProductoCategoriaService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;


class CategoriaProductoController
{
    private ProductoCategoriaService $_service;

    public function __construct(ProductoCategoriaService $service)
    {
        $this->_service = $service;
    }

    public function Create(Request $request)
    {

        $lang = $request->input('lang') ?? "es";
        $idProducto = $request->input('idProducto') ?? 0;
        $categorias = array_map('intval', Arr::wrap($request->input('categorias')));
        $categorias = array_filter($categorias, fn($c) => $c > 0);

        $dto = new CategoriaProductoDto();
        $dto->Categorias = $categorias;
        $dto->IdProducto = $idProducto;

        $respuesta = $this->_service->Create($dto, $lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
