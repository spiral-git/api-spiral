<?php

namespace App\Http\Controllers;

use App\Application\Services\ProductoPlanService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductoPlanController extends BaseController
{
    private UsuarioService $_usuarioService;

    private TipoUsuarioService $_tipoUsuarioService;

    // private ProductoPlanService $_service;

    public function __construct(UsuarioService $usuarioService, TipoUsuarioService $tipoUsuarioService)
    {
        $this->_usuarioService = $usuarioService;
        $this->_tipoUsuarioService = $tipoUsuarioService;
        // $this->_service = $service;
        parent::__construct($this->_usuarioService, $this->_tipoUsuarioService);
    }


    public function HolaMundo()
    {
 
          
        return response()->json(
            "Hola mundo",
             Response::HTTP_BAD_REQUEST
        );
    }
}
