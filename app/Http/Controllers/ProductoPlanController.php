<?php

namespace App\Http\Controllers;

use App\Application\Services\ProductoPlanService;
use App\Application\Services\TipoUsuarioService;
use App\Application\Services\UsuarioService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductoPlanController extends BaseController
{

    public function __construct()
    {
    }


    public function HolaMundo()
    {
 
          
        return response()->json(
            "Hola mundo",
             Response::HTTP_BAD_REQUEST
        );
    }
}
