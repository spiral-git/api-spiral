<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class BaseDocs
{
    // GETALL TIPO CUPON------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-cupon/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipo de cupón",
        parameters: [
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
    public function getall_tipo_cupon()
    {
    }


    // GETALL TIPO PAGO------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-pago/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipo de pago",
        parameters: [
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
    public function getall_tipo_pago()
    {
    }

    // GETALL TIPO LENGUAJE------------------------------------------------------------------
    #[OA\Get(
        path: "/base/lenguajes/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los lenguajes",
        parameters: [
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
    public function getall_lenguajes()
    {
    }

    // GETALL PAISES ------------------------------------------------------------------
    #[OA\Get(
        path: "/base/paises/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los paises",
        parameters: [
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
    public function getall_paises()
    {
    }

    // GETALL TIPO PRODUCTO ------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-producto/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipos de productos",
        parameters: [
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
    public function getall_tipo_producto()
    {
    }


    // GETALL TIPO SETUP ------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-setup/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipos de setup",
        parameters: [
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
    public function getall_tipo_setup()
    {
    }


     // GETALL TIPO USUARIOS ------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-usuario/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipos de usuarios",
        parameters: [
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
    public function getall_tipo_usuario()
    {
    }


     // GETALL TIPO DESCUENTO ------------------------------------------------------------------
    #[OA\Get(
        path: "/base/tipo-descuento/{lang}",
        tags: ["Base"],
        summary: "Obtiene todos los tipos de descuento",
        parameters: [
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
    public function getall_tipo_descuento()
    {
    }

}
