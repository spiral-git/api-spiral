<?php

namespace App\Docs;
use OpenApi\Attributes as OA;

class ProductoDocs
{

    //CREATED PRODUCTO BASE------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-base",
        tags: ["Producto"],
        summary: "Crea producto base",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idTipoProducto", "idTipoPago", "idLenguaje", "nombre", "descripcion", "lang"],
                properties: [
                    new OA\Property(property: "idTipoProducto", type: "integer", example: 0),
                    new OA\Property(property: "idTipoPago", type: "integer", example: 0),
                    new OA\Property(property: "idLenguaje", type: "integer", example: 0),
                    new OA\Property(property: "nombre", type: "string", example: ""),
                    new OA\Property(property: "descripcion", type: "string", example: ""),
                    new OA\Property(property: "lang", type: "string", example: "")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "producto base creado correctamente"
            )
        ]
    )]
    public function created_producto_base()
    {
    }

    //CREATED PRODUCTO IMAGENES------------------------------------------------------------------
    #[OA\Post(
        path: "/producto/create-imagen",
        tags: ["Producto"],
        summary: "Añadir imagenes al producto",
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["idProducto", "imagenes", "lang"],
                properties: [
                    new OA\Property(
                        property: "idProducto",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "imagenes",
                        type: "array",
                        items: new OA\Items(type: "string"),
                        example: [
                            "https://misitio.com/img1.jpg",
                            "https://misitio.com/img2.jpg"
                        ]
                    ),
                    new OA\Property(
                        property: "lang",
                        type: "string",
                        example: "es"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "producto base creado correctamente"
            )
        ]
    )]
    public function created_producto_imagenes()
    {
    }

    //     Route::post('/create-pais', [PaisProductoController::class, 'Create']);
//     Route::post('/create-categoria', [CategoriaProductoController::class, 'Create']);
//     Route::post('/create-cotizacion', [ProductoCotizacionController::class, 'Create']);
//     Route::post('/create-basico', [ProductoBasicoController::class, 'Create']);

}
