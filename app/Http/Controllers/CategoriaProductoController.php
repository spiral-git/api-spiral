<?php

namespace App\Http\Controllers;

use App\Application\DTOs\CategoriaProducto\CategoriaProductoDto;
use App\Application\Services\ProductoCategoriaService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriaProductoController
{
    private ProductoCategoriaService $_service;

    public function __construct(ProductoCategoriaService $service)
    {
        $this->_service = $service;
    } 

    public function Create(Request $request)
    {
        
        $lang   = $request->input('lang') ?? "es";
        $idProducto   = $request->input('idProducto') ?? 0;
        $idCategoria   = $request->input('idCategoria') ?? 0;

        $dto = new CategoriaProductoDto();
        $dto->IdCategoria = $idCategoria;
        $dto->IdProducto = $idProducto;

        $respuesta = $this->_service->Create($dto,$lang);

        return response()->json(
            $respuesta,
            $respuesta->IsSuccess ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST
        );
    }
}
