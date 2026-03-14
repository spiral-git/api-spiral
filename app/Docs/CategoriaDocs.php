<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class CategoriaDocs
{
    #[OA\Get(
        path: "/categoria/{idlenguaje}/{lang}",
        tags: ["Categoria"],
        summary: "Obtiene todas las categorías",
        parameters: [
            new OA\Parameter(
                name: "idlenguaje",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "lang",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Operación exitosa"
            )
        ]
    )]
    public function getall()
    {
    }

}
