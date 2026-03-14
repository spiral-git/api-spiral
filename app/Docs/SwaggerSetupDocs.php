<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class SwaggerSetupDocs
{
    #[OA\Get(
        path: "/swagger-setup",
        tags: ["Start"],
        summary: "Configuracion swagger",
        description: 'Ajusta los esquemas de la documuentación de swagger.',
        responses: [
            new OA\Response(
                response: 200,
                description: "Operación exitosa"
            )
        ]
    )]

    public function setup()
    {
    }
}
