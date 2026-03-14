<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class CategoriaDocs
{
    // GETALL------------------------------------------------------------------

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


    //CREATED------------------------------------------------------------------


    #[OA\Post(
        path: "/categoria/create",
        tags: ["Categoria"],
        summary: "Crea una nueva categoría",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "idLenguaje", "lang"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Tecnología"),
                    new OA\Property(property: "idLenguaje", type: "integer", example: 1),
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Categoría creada correctamente"
            )
        ]
    )]
    public function create()
    {
    }

    //UPDATE------------------------------------------------------------------


    #[OA\Put(
        path: "/categoria/update",
        tags: ["Categoria"],
        summary: "Actualiza una categoría",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["id", "name", "idLenguaje", "status", "lang"],
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Tecnología"),
                    new OA\Property(property: "idLenguaje", type: "integer", example: 1),
                    new OA\Property(property: "status", type: "boolean", example: true),
                    new OA\Property(property: "lang", type: "string", example: "es")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Categoría actualizada correctamente"
            )
        ]
    )]
    public function update()
    {
    }
}
