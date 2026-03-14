<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class StartDocs
{
    #[OA\Get(
        path: "/start",
        tags: ["Start"],
        summary: "Configuración Inicial",
        description: 'Configura el proyecto para que pueda funcionar.',
    )]
    public function start(){}
    
}
