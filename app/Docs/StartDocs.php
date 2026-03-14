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
        responses: [
            new OA\Response(
                response: 200,
                description: "Operación exitosa"
            )
        ]
    )]
    public function start()
    {
    }

}
